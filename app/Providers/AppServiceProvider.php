<?php

namespace App\Providers;

use App\Services\Ai\GeminiClient;
use App\Services\Ai\LlmClient;
use Composer\CaBundle\CaBundle;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(LlmClient::class, function () {
            return new GeminiClient(
                apiKey: config('services.gemini.key'),
                baseUrl: config('services.gemini.url'),
                timeoutSeconds: (int) config('services.gemini.timeout', 30),
            );
        });
    }

    public function boot(): void
    {
        Vite::useBuildDirectory('build');

        // Keep TLS verification ON everywhere, even on machines (typically
        // Windows dev boxes) whose PHP has no CA bundle configured.
        // composer/ca-bundle prefers the system bundle and falls back to
        // its own up-to-date cacert.pem.
        Http::globalOptions([
            'verify' => CaBundle::getSystemCaRootBundlePath(),
        ]);
    }
}
