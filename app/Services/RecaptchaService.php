<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecaptchaService
{
    protected $secretKey;
    protected $siteKey;
    protected $enabled;

    public function __construct()
    {
        $this->secretKey = Setting::get('recaptcha_secret_key');
        $this->siteKey = Setting::get('recaptcha_site_key');
        $this->enabled = Setting::get('recaptcha_enabled', false);
    }

    /**
     * Verify reCAPTCHA token
     *
     * @param string|null $token
     * @param string|null $remoteIp
     * @return bool
     */
    public function verify(?string $token, ?string $remoteIp = null): bool
    {
        // If reCAPTCHA is disabled, skip verification
        if (!$this->enabled || empty($this->secretKey)) {
            return true;
        }

        if (empty($token)) {
            return false;
        }

        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $this->secretKey,
                'response' => $token,
                'remoteip' => $remoteIp ?? request()->ip(),
            ]);

            $result = $response->json();

            if ($result['success'] ?? false) {
                // For reCAPTCHA v3, also check the score (0.0 to 1.0)
                // Typically, scores above 0.5 are considered legitimate
                $score = $result['score'] ?? 1.0;
                return $score >= 0.5;
            }

            return false;
        } catch (\Exception $e) {
            Log::error('reCAPTCHA verification error: ' . $e->getMessage());
            // In case of error, allow the request to proceed (fail open)
            // You can change this to return false for stricter security
            return true;
        }
    }

    /**
     * Get site key for frontend
     *
     * @return string|null
     */
    public function getSiteKey(): ?string
    {
        return $this->enabled ? $this->siteKey : null;
    }

    /**
     * Check if reCAPTCHA is enabled
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled && !empty($this->siteKey) && !empty($this->secretKey);
    }
}

