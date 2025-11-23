<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ResumeParserService
{
    public function parseResume(string $filePath, string $mimeType): array
    {
        try {
            $text = $this->extractText($filePath, $mimeType);
            
            if (empty($text)) {
                Log::warning('Resume parsing: No text extracted from file', ['path' => $filePath, 'mime' => $mimeType]);
                return $this->getEmptyResult();
            }
            
            return [
                'name' => $this->extractName($text),
                'email' => $this->extractEmail($text),
                'phone' => $this->extractPhone($text),
                'linkedin' => $this->extractLinkedIn($text),
                'skills' => $this->extractSkills($text),
                'summary' => $this->extractSummary($text),
                'educations' => $this->extractEducations($text),
                'experiences' => $this->extractExperiences($text),
                'references' => $this->extractReferences($text),
                'hobbies' => $this->extractHobbies($text),
            ];
        } catch (\Exception $e) {
            Log::error('Resume parsing error', ['error' => $e->getMessage(), 'path' => $filePath]);
            return $this->getEmptyResult();
        }
    }

    protected function getEmptyResult(): array
    {
        return [
            'name' => null,
            'email' => null,
            'phone' => null,
            'linkedin' => null,
            'skills' => [],
            'summary' => null,
            'educations' => [],
            'experiences' => [],
            'references' => [],
            'hobbies' => [],
        ];
    }

    protected function extractText(string $filePath, string $mimeType): string
    {
        $fullPath = Storage::disk('public')->path($filePath);

        if (!file_exists($fullPath)) {
            Log::error('Resume file not found', ['path' => $fullPath]);
            return '';
        }

        if ($mimeType === 'application/pdf') {
            return $this->extractTextFromPdf($fullPath);
        } elseif (in_array($mimeType, ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])) {
            return $this->extractTextFromWord($fullPath, $mimeType);
        }

        return '';
    }

    protected function extractTextFromPdf(string $filePath): string
    {
        // Try using smalot/pdfparser if available
        if (class_exists('\Smalot\PdfParser\Parser')) {
            try {
                $parser = new \Smalot\PdfParser\Parser();
                $pdf = $parser->parseFile($filePath);
                return $pdf->getText();
            } catch (\Exception $e) {
                Log::warning('PDF parsing with smalot/pdfparser failed', ['error' => $e->getMessage()]);
            }
        }

        // Fallback: Try using pdftotext command if available
        if (function_exists('shell_exec') && $this->commandExists('pdftotext')) {
            try {
                $text = shell_exec("pdftotext -layout \"$filePath\" - 2>/dev/null");
                if ($text) {
                    return $text;
                }
            } catch (\Exception $e) {
                Log::warning('PDF parsing with pdftotext command failed', ['error' => $e->getMessage()]);
            }
        }

        return '';
    }

    protected function extractTextFromWord(string $filePath, string $mimeType): string
    {
        // Try using PhpOffice\PhpWord if available
        if (class_exists('\PhpOffice\PhpWord\IOFactory')) {
            try {
                $phpWord = \PhpOffice\PhpWord\IOFactory::load($filePath);
                $text = '';
                foreach ($phpWord->getSections() as $section) {
                    foreach ($section->getElements() as $element) {
                        if (method_exists($element, 'getText')) {
                            $text .= $element->getText() . "\n";
                        }
                    }
                }
                return $text;
            } catch (\Exception $e) {
                Log::warning('Word parsing with PhpOffice\PhpWord failed', ['error' => $e->getMessage()]);
            }
        }

        // Fallback: Try using antiword or catdoc for .doc files
        if ($mimeType === 'application/msword' && function_exists('shell_exec')) {
            if ($this->commandExists('antiword')) {
                try {
                    $text = shell_exec("antiword \"$filePath\" 2>/dev/null");
                    if ($text) {
                        return $text;
                    }
                } catch (\Exception $e) {
                    Log::warning('Word parsing with antiword failed', ['error' => $e->getMessage()]);
                }
            } elseif ($this->commandExists('catdoc')) {
                try {
                    $text = shell_exec("catdoc \"$filePath\" 2>/dev/null");
                    if ($text) {
                        return $text;
                    }
                } catch (\Exception $e) {
                    Log::warning('Word parsing with catdoc failed', ['error' => $e->getMessage()]);
                }
            }
        }

        // For .docx files, try unzip and read XML
        if ($mimeType === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
            return $this->extractTextFromDocx($filePath);
        }

        return '';
    }

    protected function extractTextFromDocx(string $filePath): string
    {
        if (!class_exists('ZipArchive')) {
            Log::warning('ZipArchive class not available for DOCX parsing');
            return '';
        }

        try {
            $zip = new \ZipArchive();
            if ($zip->open($filePath) === true) {
                $text = '';
                if (($index = $zip->locateName('word/document.xml')) !== false) {
                    $data = $zip->getFromIndex($index);
                    if ($data) {
                        // Remove XML tags and extract text
                        $text = preg_replace('/<[^>]+>/', ' ', $data);
                        $text = preg_replace('/\s+/', ' ', $text);
                        $text = html_entity_decode($text, ENT_QUOTES | ENT_XML1, 'UTF-8');
                        $text = trim($text);
                    }
                }
                $zip->close();
                return $text;
            }
        } catch (\Exception $e) {
            Log::warning('DOCX parsing with ZipArchive failed', ['error' => $e->getMessage()]);
        }

        return '';
    }

    protected function commandExists(string $command): bool
    {
        $whereIsCommand = (PHP_OS == 'WINNT') ? 'where' : 'which';
        $process = @proc_open(
            "$whereIsCommand $command",
            [
                0 => ['pipe', 'r'],
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w'],
            ],
            $pipes
        );
        if ($process !== false) {
            $return = proc_close($process);
            return $return === 0;
        }
        return false;
    }

    protected function extractName(string $text): ?string
    {
        // Look for name at the beginning of the document (usually first 2-3 lines)
        $lines = array_slice(explode("\n", trim($text)), 0, 3);
        foreach ($lines as $line) {
            $line = trim($line);
            if (strlen($line) > 2 && strlen($line) < 50 && !preg_match('/@|http|www\.|phone|email/i', $line)) {
                return $line;
            }
        }
        return null;
    }

    protected function extractEmail(string $text): ?string
    {
        if (preg_match('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $text, $matches)) {
            return $matches[0];
        }
        return null;
    }

    protected function extractPhone(string $text): ?string
    {
        // Match various phone formats
        if (preg_match('/(\+?\d{1,3}[-.\s]?)?\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4}/', $text, $matches)) {
            return trim($matches[0]);
        }
        return null;
    }

    protected function extractLinkedIn(string $text): ?string
    {
        if (preg_match('/linkedin\.com\/in\/[\w-]+/i', $text, $matches)) {
            return 'https://' . $matches[0];
        }
        return null;
    }

    protected function extractSkills(string $text): array
    {
        $commonSkills = [
            'Project Management', 'Sales', 'Marketing', 'Customer Support', 'JavaScript', 'PHP',
            'Laravel', 'React', 'UI/UX', 'Data Analysis', 'Finance', 'HR', 'Operations',
            'Business Development', 'Cloud Computing', 'Product Management', 'Copywriting',
            'DevOps', 'Cybersecurity', 'Python', 'Java', 'C++', 'SQL', 'HTML', 'CSS',
            'Node.js', 'Vue.js', 'Angular', 'TypeScript', 'Docker', 'Kubernetes', 'AWS',
            'Azure', 'Git', 'Agile', 'Scrum', 'Machine Learning', 'AI', 'Blockchain'
        ];

        $foundSkills = [];
        $textLower = strtolower($text);
        
        foreach ($commonSkills as $skill) {
            if (stripos($text, $skill) !== false) {
                $foundSkills[] = $skill;
            }
        }

        return array_unique($foundSkills);
    }

    protected function extractSummary(string $text): ?string
    {
        // Look for summary/objective section
        if (preg_match('/(?:summary|objective|profile|about)[\s:]*\n(.*?)(?=\n\n|\n(?:experience|education|skills|work))/is', $text, $matches)) {
            $summary = trim($matches[1]);
            return strlen($summary) > 20 ? substr($summary, 0, 500) : null;
        }
        return null;
    }

    protected function extractEducations(string $text): array
    {
        $educations = [];
        
        // Look for education section
        if (preg_match('/(?:education|academic|qualification)[\s:]*\n(.*?)(?=\n\n|\n(?:experience|work|skills|reference)|$)/is', $text, $sectionMatch)) {
            $educationText = $sectionMatch[1];
            
            // Split by common patterns
            $entries = preg_split('/\n(?=\d{4}|\w+\s+\d{4}|[A-Z][a-z]+\s+University|[A-Z][a-z]+\s+College|[A-Z][a-z]+\s+Institute)/i', $educationText);
            
            foreach ($entries as $entry) {
                $entry = trim($entry);
                if (strlen($entry) < 20) continue;

                $education = [
                    'institution' => $this->extractInstitution($entry),
                    'degree' => $this->extractDegree($entry),
                    'field_of_study' => $this->extractFieldOfStudy($entry),
                    'start_date' => $this->extractStartDate($entry),
                    'end_date' => $this->extractEndDate($entry),
                    'description' => $this->extractDescription($entry),
                ];

                if ($education['institution']) {
                    $educations[] = $education;
                }
            }
        }

        return $educations;
    }

    protected function extractExperiences(string $text): array
    {
        $experiences = [];
        
        // Look for experience/work section
        if (preg_match('/(?:experience|work|employment|career)[\s:]*\n(.*?)(?=\n\n|\n(?:education|skills|reference|hobby)|$)/is', $text, $sectionMatch)) {
            $experienceText = $sectionMatch[1];
            
            // Split by date patterns or company names
            $entries = preg_split('/\n(?=\d{4}|\w+\s+\d{4}|[A-Z][a-z]+\s+(?:Inc|LLC|Ltd|Corp|Company)|Present|Current)/i', $experienceText);
            
            foreach ($entries as $entry) {
                $entry = trim($entry);
                if (strlen($entry) < 20) continue;

                $experience = [
                    'company_name' => $this->extractCompany($entry),
                    'role_title' => $this->extractJobTitle($entry),
                    'start_date' => $this->extractStartDate($entry),
                    'end_date' => $this->extractEndDate($entry),
                    'is_current' => preg_match('/present|current|now/i', $entry) ? true : false,
                    'achievements' => $this->extractAchievements($entry),
                ];

                if ($experience['company_name'] || $experience['role_title']) {
                    $experiences[] = $experience;
                }
            }
        }

        return $experiences;
    }

    protected function extractReferences(string $text): array
    {
        $references = [];
        
        if (preg_match('/(?:reference|referee)[\s:]*\n(.*?)(?=\n\n|$)/is', $text, $sectionMatch)) {
            $referenceText = $sectionMatch[1];
            $entries = preg_split('/\n(?=[A-Z][a-z]+\s+[A-Z][a-z]+)/', $referenceText);
            
            foreach ($entries as $entry) {
                $entry = trim($entry);
                if (strlen($entry) < 10) continue;

                $reference = [
                    'name' => $this->extractName($entry),
                    'title' => $this->extractJobTitle($entry),
                    'company' => $this->extractCompany($entry),
                    'email' => $this->extractEmail($entry),
                    'phone' => $this->extractPhone($entry),
                    'notes' => $entry,
                ];

                if ($reference['name']) {
                    $references[] = $reference;
                }
            }
        }

        return $references;
    }

    protected function extractHobbies(string $text): array
    {
        $hobbies = [];
        
        if (preg_match('/(?:hobby|hobbies|interest|interests)[\s:]*\n(.*?)(?=\n\n|$)/is', $text, $sectionMatch)) {
            $hobbyText = $sectionMatch[1];
            $items = preg_split('/[,;•\n]/', $hobbyText);
            
            foreach ($items as $item) {
                $item = trim($item);
                if (strlen($item) > 2 && strlen($item) < 100) {
                    $hobbies[] = ['name' => $item, 'description' => null];
                }
            }
        }

        return $hobbies;
    }

    // Helper methods
    protected function extractInstitution(string $text): ?string
    {
        if (preg_match('/(?:University|College|Institute|School|Academy|Académie)\s+of\s+([A-Z][a-z]+(?:\s+[A-Z][a-z]+)*)/i', $text, $matches)) {
            return $matches[0];
        }
        if (preg_match('/([A-Z][a-z]+(?:\s+[A-Z][a-z]+)*)\s+(?:University|College|Institute|School)/i', $text, $matches)) {
            return $matches[0];
        }
        return null;
    }

    protected function extractDegree(string $text): ?string
    {
        if (preg_match('/(Bachelor|Master|PhD|Doctorate|Diploma|Certificate|Associate)\s+(?:of|in)?\s*([A-Z][a-z]+(?:\s+[A-Z][a-z]+)*)?/i', $text, $matches)) {
            return trim($matches[0]);
        }
        return null;
    }

    protected function extractFieldOfStudy(string $text): ?string
    {
        if (preg_match('/(?:in|of)\s+([A-Z][a-z]+(?:\s+[A-Z][a-z]+)*)/i', $text, $matches)) {
            return $matches[1];
        }
        return null;
    }

    protected function extractCompany(string $text): ?string
    {
        // Look for company names (usually after job title)
        $lines = explode("\n", $text);
        foreach ($lines as $line) {
            $line = trim($line);
            if (preg_match('/([A-Z][a-z]+(?:\s+[A-Z][a-z]+)*)\s*(?:Inc|LLC|Ltd|Corp|Company|Technologies|Solutions)?/i', $line, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

    protected function extractJobTitle(string $text): ?string
    {
        // Look for common job titles
        $lines = explode("\n", $text);
        foreach ($lines as $line) {
            $line = trim($line);
            if (preg_match('/(Senior\s+)?(Software|Product|Project|Marketing|Sales|Business|Data|DevOps|UI\/UX|Full\s+Stack)\s+(Engineer|Manager|Developer|Analyst|Designer|Specialist|Lead|Architect)/i', $line, $matches)) {
                return $line;
            }
        }
        return null;
    }

    protected function extractStartDate(string $text): ?string
    {
        if (preg_match('/(\d{4})\s*[-–]\s*(\d{4}|Present|Current)/i', $text, $matches)) {
            return $matches[1] . '-01-01';
        }
        return null;
    }

    protected function extractEndDate(string $text): ?string
    {
        if (preg_match('/\d{4}\s*[-–]\s*(\d{4})/i', $text, $matches)) {
            return $matches[1] . '-12-31';
        }
        return null;
    }

    protected function extractDescription(string $text): ?string
    {
        $lines = explode("\n", $text);
        $description = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if (strlen($line) > 20 && !preg_match('/\d{4}/', $line)) {
                $description[] = $line;
            }
        }
        return !empty($description) ? implode("\n", array_slice($description, 0, 3)) : null;
    }

    protected function extractAchievements(string $text): ?string
    {
        $lines = explode("\n", $text);
        $achievements = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if ((strlen($line) > 20) && (preg_match('/•|[-*]|^\d+\./', $line) || preg_match('/achieved|improved|increased|developed|managed/i', $line))) {
                $achievements[] = preg_replace('/^[•\-\*\d+\.]\s*/', '', $line);
            }
        }
        return !empty($achievements) ? implode("\n", array_slice($achievements, 0, 5)) : null;
    }
}

