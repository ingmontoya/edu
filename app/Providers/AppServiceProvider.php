<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // AI endpoints: max 30 calls per minute per institution
        RateLimiter::for('ai-analysis', function (Request $request) {
            $institutionId = $request->user()?->institution_id ?? 'guest';

            return Limit::perMinute(30)->by('ai:'.$institutionId)
                ->response(fn () => response()->json([
                    'message' => 'Demasiadas solicitudes de análisis. Espera un momento antes de continuar.',
                ], 429));
        });
    }
}
