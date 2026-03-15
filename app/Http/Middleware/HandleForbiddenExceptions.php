<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Illuminate\Auth\Access\AuthorizationException;

class HandleForbiddenExceptions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $response = $next($request);

            // Cek jika response adalah 403
            if ($response->status() === 403) {
                return $this->handleForbidden($request);
            }

            return $response;
        } catch (AccessDeniedHttpException|AuthorizationException $e) {
            return $this->handleForbidden($request);
        }
    }

    protected function handleForbidden(Request $request): Response
    {
        $message = 'Anda tidak memiliki akses ke halaman ini.';

        // Cek apakah request ke admin panel
        if ($request->is('admin/*') || str_starts_with($request->path(), 'admin')) {
            if ($request->expectsJson() || $request->header('X-Livewire') || $request->header('x-livewire')) {
                return response()->json([
                    'message' => $message,
                    'redirect' => '/admin',
                    'notification' => [
                        'message' => $message,
                        'type' => 'danger'
                    ]
                ], 403);
            }

            // Gunakan toResponse() untuk mengembalikan Response object yang valid
            return redirect('/admin')
                ->with('notification', $message)
                ->with('notification_type', 'danger')
                ->toResponse($request);
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => $message], 403);
        }

        return redirect('/admin')
            ->with('notification', $message)
            ->with('notification_type', 'danger')
            ->toResponse($request);
    }
}
