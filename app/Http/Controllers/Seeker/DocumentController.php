<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use App\Models\JobDocument;
use App\Services\ResumeParserService;
use App\Services\StorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $seeker = $request->user('seeker');
        $documents = $seeker->documents()
            ->orderByDesc('is_default')
            ->latest()
            ->get();

        return view('seeker.documents.index', compact('documents'));
    }

    public function store(Request $request, StorageService $storageService)
    {
        $seeker = $request->user('seeker');

        $validated = $request->validate([
            'type' => 'required|in:resume,cover_letter',
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'set_default' => 'sometimes|boolean',
            'parse_resume' => 'sometimes|boolean',
        ]);

        $file = $request->file('file');
        $path = $storageService->storeFile($file, 'job-documents');

        $shouldBeDefault = $request->boolean('set_default')
            || !$seeker->documents()
                ->where('type', $validated['type'])
                ->where('is_default', true)
                ->exists();

        if ($shouldBeDefault) {
            $seeker->documents()
                ->where('type', $validated['type'])
                ->update(['is_default' => false]);
        }

        $document = $seeker->documents()->create([
            'type' => $validated['type'],
            'title' => $validated['title'],
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'mime_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'is_default' => $shouldBeDefault,
        ]);

        // Parse resume if requested
        if ($validated['type'] === 'resume' && $request->boolean('parse_resume')) {
            try {
                $parser = new ResumeParserService();
                $parsedData = $parser->parseResume($path, $file->getClientMimeType());
                
                // Check if we got any useful data
                $hasData = !empty($parsedData['name']) || 
                          !empty($parsedData['email']) || 
                          !empty($parsedData['skills']) || 
                          !empty($parsedData['educations']) || 
                          !empty($parsedData['experiences']);
                
                if ($hasData) {
                    $this->applyParsedData($seeker, $parsedData);
                    $itemsCount = count($parsedData['educations']) + count($parsedData['experiences']) + count($parsedData['references']);
                    return back()->with('success', "Resume uploaded and parsed successfully! Found {$itemsCount} items. Your profile has been updated.");
                } else {
                    return back()->with('warning', 'Resume uploaded successfully, but we couldn\'t extract information from it. Please manually update your profile. Note: PDF/Word parsing libraries may need to be installed.');
                }
            } catch (\Exception $e) {
                \Log::error('Resume parsing error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
                return back()->with('warning', 'Resume uploaded successfully, but parsing encountered an error. You can manually update your profile.');
            }
        }

        return back()->with('success', ucfirst(str_replace('_', ' ', $validated['type'])) . ' uploaded successfully.');
    }

    public function destroy(Request $request, JobDocument $document)
    {
        $this->authorizeDocument($request, $document);

        if ($document->is_default) {
            return back()->with('error', 'Cannot delete a default document. Please set another default first.');
        }

        $storageService = app(StorageService::class);
        $storageService->delete($document->file_path);
        $document->delete();

        return back()->with('success', 'Document deleted successfully.');
    }

    public function makeDefault(Request $request, JobDocument $document)
    {
        $this->authorizeDocument($request, $document);

        $request->user('seeker')
            ->documents()
            ->where('type', $document->type)
            ->update(['is_default' => false]);

        $document->update(['is_default' => true]);

        return back()->with('success', 'Default document updated.');
    }

    protected function authorizeDocument(Request $request, JobDocument $document): void
    {
        if ($document->seeker_id !== $request->user('seeker')->id) {
            abort(403);
        }
    }

    protected function applyParsedData($seeker, array $parsedData): void
    {
        // Update profile fields
        if ($parsedData['name'] && !$seeker->name) {
            $seeker->name = $parsedData['name'];
        }
        if ($parsedData['email'] && !$seeker->email) {
            $seeker->email = $parsedData['email'];
        }
        if ($parsedData['phone'] && !$seeker->phone) {
            $seeker->phone = $parsedData['phone'];
        }
        if ($parsedData['linkedin'] && !$seeker->linkedin_url) {
            $seeker->linkedin_url = $parsedData['linkedin'];
        }
        if ($parsedData['summary'] && !$seeker->resume_bio) {
            $seeker->resume_bio = $parsedData['summary'];
        }
        if ($parsedData['summary'] && !$seeker->about) {
            $seeker->about = $parsedData['summary'];
        }
        if (!empty($parsedData['skills'])) {
            $existingSkills = $seeker->skills ? explode(',', $seeker->skills) : [];
            $allSkills = array_unique(array_merge($existingSkills, $parsedData['skills']));
            $seeker->skills = implode(',', $allSkills);
        }
        $seeker->save();

        // Add educations
        foreach ($parsedData['educations'] as $edu) {
            if ($edu['institution']) {
                $seeker->educations()->create($edu);
            }
        }

        // Add experiences
        foreach ($parsedData['experiences'] as $exp) {
            if ($exp['company_name'] || $exp['role_title']) {
                $seeker->experiences()->create($exp);
            }
        }

        // Add references
        foreach ($parsedData['references'] as $ref) {
            if ($ref['name']) {
                $seeker->references()->create($ref);
            }
        }

        // Add hobbies
        foreach ($parsedData['hobbies'] as $hobby) {
            if ($hobby['name']) {
                $seeker->hobbies()->create($hobby);
            }
        }
    }
}
