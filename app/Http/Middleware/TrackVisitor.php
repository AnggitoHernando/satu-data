<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class TrackVisitor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($this->shouldTrack($request, $response)) {
            try {
                $this->trackUniqueVisitor($request);
                $this->trackPageView($request);
            } catch (\Throwable $e) {
                // jangan sampai error pencatatan merusak halaman
                report($e);
            }
        }

        return $response;
    }
    private function shouldTrack(Request $request, Response $response): bool
    {
        if (!$request->isMethod('get')) return false;
        if (!$response->isSuccessful()) return false;          // hanya status 200
        if ($request->is('admin/*', 'login', 'logout', 'up')) return false;

        // abaikan AJAX biasa, tapi izinkan navigasi Inertia
        if ($request->ajax() && !$request->header('X-Inertia')) return false;

        // abaikan partial reload Inertia (bukan pindah halaman)
        if ($request->header('X-Inertia-Partial-Component')) return false;

        // abaikan prefetch
        if ($request->header('Purpose') === 'prefetch') return false;

        return !$this->isBot($request->userAgent());
    }

    private function trackUniqueVisitor(Request $request): void
    {
        DB::table('visitors')->insertOrIgnore([
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url'        => '/' . ltrim($request->path(), '/'),
            'visited_at' => now()->toDateString(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function trackPageView(Request $request): void
    {
        DB::table('page_views')->upsert(
            [[
                'path'       => '/' . ltrim($request->path(), '/'),
                'date'       => now()->toDateString(),
                'hits'       => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]],
            ['path', 'date'],                              // kunci unik
            ['hits' => DB::raw('page_views.hits + 1'), 'updated_at' => now()]
        );
    }

    private function isBot(?string $ua): bool
    {
        return !$ua || preg_match('/bot|crawl|spider|slurp|curl|wget|facebookexternalhit/i', $ua);
    }
}
