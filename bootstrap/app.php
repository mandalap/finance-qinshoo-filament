<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle authorization exceptions (403)
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException ||
                $e instanceof \Illuminate\Auth\Access\AuthorizationException) {

                $message = 'Anda tidak memiliki akses ke halaman ini.';

                // Untuk Filament admin, redirect ke dashboard
                if ($request->is('admin/*') || str_starts_with($request->path(), 'admin')) {
                    if ($request->expectsJson() || $request->header('X-Livewire')) {
                        // Untuk Livewire/Fetch requests, return custom JSON response
                        return response()->json([
                            'message' => $message,
                            'redirect' => '/admin',
                            'notification' => [
                                'message' => $message,
                                'type' => 'danger'
                            ]
                        ], 403);
                    }

                    // Untuk request biasa, redirect ke dashboard dengan flash
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
        });

        // Handle 404 errors di admin panel
        $exceptions->render(function (Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, Request $request) {
            if ($request->is('admin/*') || str_starts_with($request->path(), 'admin')) {
                return redirect('/admin')
                    ->with('warning', 'Halaman tidak ditemukan.')
                    ->toResponse($request);
            }
        });
    })->create();
