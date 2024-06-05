<?php

namespace App\Services\Auth;

use App\Exceptions\ServiceErrorException;
use App\Helpers\AuthHelper;
use App\Repositories\Eloquent\Referensi\KlpRepository;
use App\Repositories\Eloquent\User\MenuRepository;
use App\Repositories\Eloquent\User\PermissionRepository;
use App\Repositories\Eloquent\User\RoleHasPermissionRepository;
use App\Repositories\Eloquent\User\UserRepository;
use App\Traits\ResponseTransform;
use App\Traits\SlugHeaderRequest;
use Exception;

class AuthService
{
    use SlugHeaderRequest;
    use ResponseTransform;

    /**
     * constructor.
     *
     * @param AuthHelper $authHelper
     * @param UserRepository $repositoryUser
     */
    public function __construct(
        protected AuthHelper $authHelper = new AuthHelper(),
        protected UserRepository $repositoryUser = new UserRepository(),
        protected MenuRepository $repositoryMenu = new MenuRepository(),
        protected PermissionRepository $repositoryPermission = new PermissionRepository(),
        protected RoleHasPermissionRepository $repositoryRoleHasPermission = new RoleHasPermissionRepository(),
        protected KlpRepository $repositoryKlp = new KlpRepository(),
    ) {
    }

    /**
     * Verify Credential Login .
     ** @param array $data
     *rn string
     *
     * @throws ServiceErrorException
     * @throws Exception
     */
    public function verifyCredential(array $data)
    {
        # Check User
        $user = $this->repositoryUser->findBy(
            key: [
                'email' => $data['email'],
            ]
        );

        if (! $user) {
            throw new ServiceErrorException('Credential tidak valid', 422);
        }

        # Validate password
        if (\Hash::check($data['password'], $user->password) === false) {
            throw new ServiceErrorException('Credential tidak valid', 422);
        }

        # Get Status Force Change Password & IP Address Client Concurrently
        $params = [
            'fullname' => $user->fullname,
            'email' => $user->email,
            'user_id' => $user->id,
        ];

        $token = $this->authHelper->storeAuthToken($params);
        if (! $token) {
            throw new ServiceErrorException('Gagal login', 422);
        }

        $roles = $user->roles()->get();
        $roleIds = $roles->pluck('role_id')->toArray();
        $klpIds = $roles->pluck('ref_klp_id')->unique()->toArray();

        $klp = $this->repositoryKlp->getListByIds(
            selects: [
                'id',
                'kode',
                'nama',
                'nama_pendek',
                'jenis',
                'level'
            ],
            ids: $klpIds
        )->setHidden(['logo_url']);
        $rolesWithRefKlpId = $roles->map(function ($role) {
            return [
                'role_id' => $role->role_id,
                'ref_klp_id' => $role->ref_klp_id,
            ];
        });

        $user->roles = $rolesWithRefKlpId;

        $klpWithRoleId = $klp->map(function ($klp) use ($user) {
            $role = $user->roles->firstWhere('ref_klp_id', $klp['id']);
            $klp['role_id'] = $role ? $role['role_id'] : null;
            return $klp;
        });

        $user->klp = $klpWithRoleId;
        $user->default_klp = $klpWithRoleId->first();

        $roleHasPermissions = $this->repositoryRoleHasPermission->getByRoleIds(
            roleIds: $roleIds
        );
        $permissions = $this->repositoryPermission->getByPermissionIds(
            permissionIds: $roleHasPermissions->pluck('permission_id')->toArray()
        );

        $user->permissions = $permissions->pluck('action');

        return [
            'expire_at' => $this->authHelper->timeStampTtlToken(),
            'access_token' => $token,
            'user_data' => $user,
        ];
    }

    /**
     * Log out (Invalidate the token).
     *
     * @param object $request Request
     *
     * @return bool
     *
     * @throws ServiceErrorException
     */
    public function logout()
    {
        try {
            // to do
            return true;
        } catch (\Exception $e) {
            throw new ServiceErrorException(__('auth.has_been_logout'), 422);
        }
    }
}
