<?php

if (!function_exists('storage_url')) {
    /**
     * Get the URL for a file stored in the application
     * Checks local first, then S3 if enabled
     *
     * @param string|null $path
     * @return string|null
     */
    function storage_url(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        $storageService = app(\App\Services\StorageService::class);
        return $storageService->getUrl($path);
    }
}

