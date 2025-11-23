<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class StorageService
{
    protected $useS3;
    protected $disk;

    public function __construct()
    {
        $this->useS3 = Setting::get('storage_driver', 'local') === 's3';
        $this->disk = $this->useS3 ? 's3' : 'public';
        
        // Configure S3 dynamically if enabled
        if ($this->useS3) {
            $this->configureS3();
        }
    }

    /**
     * Configure S3 disk with settings from database
     */
    protected function configureS3()
    {
        $key = Setting::get('aws_access_key_id') ?: env('AWS_ACCESS_KEY_ID');
        $secret = Setting::get('aws_secret_access_key') ?: env('AWS_SECRET_ACCESS_KEY');
        $region = Setting::get('aws_default_region') ?: env('AWS_DEFAULT_REGION');
        $bucket = Setting::get('aws_bucket') ?: env('AWS_BUCKET');
        $url = Setting::get('aws_url') ?: env('AWS_URL');
        $endpoint = Setting::get('aws_endpoint') ?: env('AWS_ENDPOINT');

        if ($key && $secret && $region && $bucket) {
            config([
                'filesystems.disks.s3.key' => $key,
                'filesystems.disks.s3.secret' => $secret,
                'filesystems.disks.s3.region' => $region,
                'filesystems.disks.s3.bucket' => $bucket,
                'filesystems.disks.s3.url' => $url,
                'filesystems.disks.s3.endpoint' => $endpoint,
            ]);
        }
    }

    /**
     * Store a file and return the path
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param string|null $filename
     * @return string
     */
    public function storeFile(UploadedFile $file, string $directory, ?string $filename = null): string
    {
        // Generate unique filename to avoid conflicts
        if (!$filename) {
            $extension = $file->getClientOriginalExtension();
            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $filename = time() . '_' . uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $name) . '.' . $extension;
        } else {
            $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
        }
        
        // Ensure directory exists
        try {
            Storage::disk($this->disk)->makeDirectory($directory);
        } catch (\Exception $e) {
            Log::debug('Directory might already exist', ['directory' => $directory, 'error' => $e->getMessage()]);
        }
        
        try {
            // Store in primary location (S3 or local)
            $storedPath = Storage::disk($this->disk)->putFileAs($directory, $file, $filename);
            
            // Verify file was actually stored
            if (!Storage::disk($this->disk)->exists($storedPath)) {
                throw new \Exception("File was not stored at path: {$storedPath}");
            }
            
            // Always store a local backup for fallback (even if using S3)
            if ($this->disk !== 'public') {
                try {
                    Storage::disk('public')->makeDirectory($directory);
                    Storage::disk('public')->putFileAs($directory, $file, $filename);
                } catch (\Exception $e) {
                    Log::warning('Failed to store local backup', [
                        'error' => $e->getMessage(),
                        'path' => $directory . '/' . $filename
                    ]);
                }
            }
            
            Log::info('File stored successfully', [
                'path' => $storedPath,
                'disk' => $this->disk,
                'size' => $file->getSize(),
                'file_exists' => Storage::disk($this->disk)->exists($storedPath)
            ]);
            
            return $storedPath;
        } catch (\Exception $e) {
            Log::error('File storage error', [
                'disk' => $this->disk,
                'directory' => $directory,
                'filename' => $filename,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Fallback to local storage if S3 fails
            if ($this->useS3) {
                try {
                    Storage::disk('public')->makeDirectory($directory);
                    $fallbackPath = Storage::disk('public')->putFileAs($directory, $file, $filename);
                    Log::info('File stored in local fallback', ['path' => $fallbackPath]);
                    return $fallbackPath;
                } catch (\Exception $fallbackError) {
                    Log::error('Local fallback storage also failed', [
                        'error' => $fallbackError->getMessage()
                    ]);
                    throw $e; // Throw original error
                }
            }
            
            throw $e;
        }
    }

    /**
     * Get the URL for a file (checks local first, then S3)
     *
     * @param string $path
     * @return string|null
     */
    public function getUrl(string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        // Normalize path (remove any leading/trailing slashes and backslashes)
        $path = trim(str_replace('\\', '/', $path), '/');

        // First, try to get from local storage if file exists
        try {
            if (Storage::disk('public')->exists($path)) {
                // Use asset() helper to generate URL through the public/storage symlink
                // This ensures the URL points to public/storage which is the symlink
                $url = asset('storage/' . $path);
                if (!empty($url)) {
                    Log::debug('File URL generated from local storage', [
                        'path' => $path,
                        'url' => $url,
                        'file_exists' => true
                    ]);
                    return $url;
                }
            }
        } catch (\Exception $e) {
            Log::debug('Error checking local file', ['path' => $path, 'error' => $e->getMessage()]);
        }

        // If S3 is enabled, try S3 if file exists
        if ($this->useS3) {
            try {
                if (Storage::disk('s3')->exists($path)) {
                    $url = Storage::disk('s3')->url($path);
                    if (!empty($url)) {
                        Log::debug('File URL generated from S3', ['path' => $path, 'url' => $url]);
                        return $url;
                    }
                }
            } catch (\Exception $e) {
                Log::debug('Error checking S3 file', ['path' => $path, 'error' => $e->getMessage()]);
            }
        }


        // If all else fails, return null
        Log::warning('Could not generate URL for file', ['path' => $path]);
        return null;
    }

    /**
     * Check if file exists (checks local first, then S3)
     *
     * @param string $path
     * @return bool
     */
    public function exists(string $path): bool
    {
        if (empty($path)) {
            return false;
        }

        // Check local first
        if (Storage::disk('public')->exists($path)) {
            return true;
        }

        // Check S3 if enabled
        if ($this->useS3) {
            return Storage::disk('s3')->exists($path);
        }

        return false;
    }

    /**
     * Delete a file from both local and S3
     *
     * @param string $path
     * @return bool
     */
    public function delete(string $path): bool
    {
        $deleted = false;

        // Delete from local
        if (Storage::disk('public')->exists($path)) {
            $deleted = Storage::disk('public')->delete($path) || $deleted;
        }

        // Delete from S3 if enabled
        if ($this->useS3 && Storage::disk('s3')->exists($path)) {
            $deleted = Storage::disk('s3')->delete($path) || $deleted;
        }

        return $deleted;
    }

    /**
     * Get file contents (checks local first, then S3)
     *
     * @param string $path
     * @return string|null
     */
    public function get(string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        // Try local first
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->get($path);
        }

        // Try S3 if enabled
        if ($this->useS3 && Storage::disk('s3')->exists($path)) {
            return Storage::disk('s3')->get($path);
        }

        return null;
    }

    /**
     * Get the current storage driver
     *
     * @return string
     */
    public function getDriver(): string
    {
        return $this->useS3 ? 's3' : 'local';
    }

    /**
     * Check if S3 is enabled
     *
     * @return bool
     */
    public function isS3Enabled(): bool
    {
        return $this->useS3;
    }
}

