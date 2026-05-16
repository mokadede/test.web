<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictDirectAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $referer = $request->headers->get('referer');
        $currentPath = $request->path();
        
        // Special logic for Articles Index: 
        // If there are more than 6 articles, the "Lihat Semua" button appears.
        // In that case, we allow direct URL access.
        if ($currentPath === 'articles') {
            $allArticlesCount = \App\Models\Article::where('is_published', true)->count();
            if ($allArticlesCount > 6) {
                return $next($request);
            }
        }

        // Standard direct access check for restricted routes
        if (!$referer) {
            $errorMessage = $currentPath === 'login' 
                ? 'Akses tidak diizinkan.' 
                : 'Akses langsung via URL tidak diizinkan.';
            
            return redirect()->route('home')->with('error', $errorMessage);
        }

        $refererHost = parse_url($referer, PHP_URL_HOST);
        $currentHost = $request->getHost();

        if ($refererHost !== $currentHost) {
            $errorMessage = $currentPath === 'login' 
                ? 'Akses login tidak valid.' 
                : 'Akses tidak valid. Silakan gunakan tombol yang tersedia.';

            return redirect()->route('home')->with('error', $errorMessage);
        }

        return $next($request);
    }
}
