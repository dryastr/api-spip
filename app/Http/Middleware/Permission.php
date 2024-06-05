<?php

namespace App\Http\Middleware;

use App\Models\User\RoleHasPermission;
use App\Models\User\UserHasRole;
use App\Traits\ResponseTransform;
use Closure;

class Permission
{
    use ResponseTransform;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $action)
    {
        $userId = auth()->user()->user_id;
        if ($userId) {
            $userHasRole = UserHasRole::where('user_id', $userId)->get();
            $roleHasPermission = RoleHasPermission::leftJoin('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                ->whereIn('role_has_permissions.role_id', $userHasRole->pluck('role_id')->toArray())
                ->get()
                ->pluck('action')
                ->toArray();

            if (in_array($action, $roleHasPermission)) {
                return $next($request);
            }
        }

        return $this->responseError('Tidak ada otorisasi', 401);
    }
}
