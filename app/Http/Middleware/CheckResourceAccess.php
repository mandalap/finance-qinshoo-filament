<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckResourceAccess
{
    /**
     * Resource permissions mapping
     * Maps resource paths to their required permissions
     * Format: 'path' => 'permission-name'
     */
    protected array $resourcePermissions = [
        'admin/activity-logs' => 'view-activity-logs',
        'admin/roles' => 'view-roles',
        'admin/users' => 'view-users',
        'admin/transaksi-keuangans' => 'view-transaksi',
        'admin/budgets' => 'view-budget',
        'admin/pengajuan-barangs' => 'view-pengajuan',
        'admin/kategori-transaksis' => 'view-kategori',
    ];

    /**
     * Action permissions mapping
     * Maps action suffixes to permission types
     */
    protected array $actionPermissions = [
        '/create' => 'create',
        '/edit' => 'edit',
        '/delete' => 'delete',
    ];

    /**
     * Resource name to permission name mapping
     */
    protected array $resourceNames = [
        'transaksi-keuangans' => 'transaksi',
        'budgets' => 'budget',
        'pengajuan-barangs' => 'pengajuan',
        'kategori-transaksis' => 'kategori',
        'users' => 'users',
        'roles' => 'roles',
        'activity-logs' => 'activity-logs',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return $next($request);
        }

        // Super admin has access to everything
        if ($user->hasRole('super-admin')) {
            return $next($request);
        }

        $path = $request->path();

        // Cek permission berdasarkan path dan action
        $requiredPermission = $this->getRequiredPermission($path);

        if ($requiredPermission && !$user->can($requiredPermission)) {
            return $this->handleUnauthorized($request);
        }

        return $next($request);
    }

    /**
     * Get required permission for a given path
     */
    protected function getRequiredPermission(string $path): ?string
    {
        // Cek apakah path adalah action tertentu (create, edit)
        foreach ($this->actionPermissions as $suffix => $action) {
            if (str_ends_with($path, $suffix)) {
                // Extract resource name from path
                $resourcePath = str_replace($suffix, '', $path);
                foreach ($this->resourceNames as $urlSegment => $permissionName) {
                    if (str_contains($resourcePath, $urlSegment)) {
                        return $action . '-' . $permissionName;
                    }
                }
            }
        }

        // Cek view permission untuk resource
        foreach ($this->resourcePermissions as $resourcePath => $permission) {
            if (str_starts_with($path, $resourcePath)) {
                return $permission;
            }
        }

        return null;
    }

    protected function handleUnauthorized(Request $request): Response
    {
        $message = 'Anda tidak memiliki akses ke halaman ini.';

        // Untuk Livewire/Filament requests
        if ($request->header('X-Livewire') || $request->header('x-livewire') || $request->expectsJson()) {
            return response()->json([
                'danger' => $message,
            ], 403);
        }

        // Untuk request biasa, gunakan format notifikasi Filament
        return redirect('/admin')->with('filament.notifications', [
            [
                'status' => 'danger',
                'title' => $message,
            ],
        ]);
    }
}
