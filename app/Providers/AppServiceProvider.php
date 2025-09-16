<?php

declare(strict_types=1);

namespace App\Providers;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Facades\Response as ResponseFactory;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        ResponseFactory::macro(
            'api',
            /**
             * @param  array<string, mixed>  $data
             * @param  array<string, mixed>  $meta
             */
            function (
                ?string $message = null,
                array $data = [],
                int $status = 200,
                array $meta = []
            ): JsonResponse|RedirectResponse {
                $payload = array_filter([
                    'message' => $message,
                    'data' => $data,
                    'meta' => Arr::except($meta, ['redirectTo', 'flash']),
                ], static fn (mixed $value) => $value !== null && $value !== []);

                if (request()->expectsJson() || request()->wantsJson()) {
                    return response()->json($payload, $status);
                }

                $redirectResolver = $meta['redirectTo'] ?? null;

                $response = match (true) {
                    $redirectResolver instanceof Closure => $redirectResolver(),
                    $redirectResolver instanceof RedirectResponse => $redirectResolver,
                    is_string($redirectResolver) => redirect()->to($redirectResolver),
                    default => redirect()->back(),
                };

                if (! $response instanceof RedirectResponse) {
                    return $response;
                }

                if ($message !== null) {
                    $response->with('status', $message);
                }

                foreach (($meta['flash'] ?? []) as $key => $value) {
                    $response->with($key, $value);
                }

                return $response;
            }
        );
    }
}
