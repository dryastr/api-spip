<?php

namespace App\Services\User;

use App\Exceptions\ServiceErrorException;
use App\Helpers\AuthHelper;
use App\Repositories\Eloquent\Referensi\KlpRepository;
use App\Repositories\Eloquent\User\MenuRepository;
use App\Repositories\Eloquent\User\PermissionRepository;
use App\Repositories\Eloquent\User\RegistrationRepository;
use App\Repositories\Eloquent\User\RoleHasPermissionRepository;
use App\Repositories\Eloquent\User\UserRepository;
use App\Traits\ResponseTransform;
use App\Traits\SlugHeaderRequest;
use DB;
use Exception;

class UserService
{
    use SlugHeaderRequest;
    use ResponseTransform;

    /**
     * constructor.
     *
     * @param AuthHelper $authHelper
     * @param UserRepository $repositoryUser
     * @param MenuRepository $repositoryMenu
     */
    public function __construct(
        protected AuthHelper $authHelper = new AuthHelper(),
        protected UserRepository $repositoryUser = new UserRepository(),
        protected MenuRepository $repositoryMenu = new MenuRepository(),
        protected RegistrationRepository $repositoryRegistration = new RegistrationRepository(),
        protected PermissionRepository $repositoryPermission = new PermissionRepository(),
        protected RoleHasPermissionRepository $repositoryRoleHasPermission = new RoleHasPermissionRepository(),
        protected KlpRepository $repositoryKlp = new KlpRepository(),
    ) {
    }

    /**
     * Me / Profile
     *
     * @throws ServiceErrorException
     * @throws Exception
     */
    public function me()
    {
        return $this->authHelper->getUserProfile();
    }

    /**
     * Menu Me
     *
     * @throws ServiceErrorException
     * @throws Exception
     */
    public function menuMe()
    {
        $userId = auth()->user()->user_id;

        $user = $this->repositoryUser->findBy(
            key: [
                'id' => $userId,
            ]
        )->setHidden(['password']);

        $roleIds = $user->roles()->get()->pluck('role_id')->toArray();
        $roleHasPermissions = $this->repositoryRoleHasPermission->getByRoleIds(
            roleIds: $roleIds
        );
        $menus = $this->repositoryMenu->getAll();
        $permissions = $this->repositoryPermission->getByPermissionIds(
            permissionIds: $roleHasPermissions->pluck('permission_id')->toArray()
        );
        $accessMenus = $permissions->pluck('menu_id')->unique();

        $allMenuWithAccess = collect();
        foreach ($accessMenus as $value) {
            $menu = $menus->where('id', $value)->where('status', 'active')->first();
            if ($menu) {
                $allMenuWithAccess->push($menu);
                $parent_1 = $menus->where('id', $menu->parent_id)->first();
                if ($parent_1) {
                    $cek = $allMenuWithAccess->where('id', $parent_1->id)->first();
                    if (! $cek) {
                        $allMenuWithAccess->push($parent_1);
                    }
                    $parent_2 = $menus->where('id', $parent_1->parent_id)->first();
                    if ($parent_2) {
                        $cek = $allMenuWithAccess->where('id', $parent_2->id)->first();
                        if (! $cek) {
                            $allMenuWithAccess->push($parent_2);
                        }
                    }
                }
            }
        }

        return $this->generateMenu($allMenuWithAccess, $permissions);
    }

    /**
     * Menu Me
     *
     * @throws ServiceErrorException
     * @throws Exception
     */
    public function menuMe2()
    {
        $userId = auth()->user()->user_id;

        $user = $this->repositoryUser->findBy(
            key: [
                'id' => $userId,
            ]
        )->setHidden(['password']);

        $roleIds = $user->roles()->get()->pluck('role_id')->toArray();
        $roleHasPermissions = $this->repositoryRoleHasPermission->getByRoleIds(
            roleIds: $roleIds
        );
        $menus = $this->repositoryMenu->getAll();
        $permissions = $this->repositoryPermission->getByPermissionIds(
            permissionIds: $roleHasPermissions->pluck('permission_id')->toArray()
        );
        $accessMenus = $permissions->pluck('menu_id')->unique();

        $allMenuWithAccess = collect();
        foreach ($accessMenus as $value) {
            $menu = $menus->where('id', $value)->where('status', 'active')->first();
            if ($menu) {
                $allMenuWithAccess->push($menu);
                $parent_1 = $menus->where('id', $menu->parent_id)->first();
                if ($parent_1) {
                    $cek = $allMenuWithAccess->where('id', $parent_1->id)->first();
                    if (! $cek) {
                        $allMenuWithAccess->push($parent_1);
                    }
                    $parent_2 = $menus->where('id', $parent_1->parent_id)->first();
                    if ($parent_2) {
                        $cek = $allMenuWithAccess->where('id', $parent_2->id)->first();
                        if (! $cek) {
                            $allMenuWithAccess->push($parent_2);
                        }
                    }
                }
            }
        }

        return $this->generateMenu2($allMenuWithAccess, $permissions);
    }

    /**
     * Find By Id
     *
     * @return Collection
     */
    public function findById($id, $selects = '*', $relations = null)
    {
        return $this->repositoryUser->find($id, $selects, $relations);
    }

    /**
     * List User untuk diapprove
     *
     * @throws ServiceErrorException
     * @throws Exception
     */
    public function getListUserApproval($selects = '*', $request = null, $filter = [])
    {
        $filter['status_approval'] = ['dalam-antrian','ditolak'];
        $filter['user.email_verified_at'] = 'notnull';
        return $this->repositoryRegistration->getPaginate($selects, $request, $filter);
    }

    /**
     * Approve user
     *
     * @return Model
     */
    public function approveUser($id, $data)
    {
        // Cari data registrasi berdasarkan ID
        $query = $this->repositoryRegistration->findBy(
            key: [
                'id' => $id,
            ]
        );

        if (! $query) {
            throw new ServiceErrorException('Data not found', 422);
        }

        return DB::transaction(function () use ($data, $query) {
            // Tentukan status berdasarkan status_approval
            $status = $data['status_approval'] === 'ditolak' ? 'inactive' : 'active';

            \Log::info('User approval action for Status: ' . $status . ', Status Approval: ' . $query->status_approval . ', User ID: ' . $query->user_id);

            // Perbarui data pengguna terkait
            $userUpdate = $this->repositoryUser->findBy(
                key: [
                    'id' => $query->user_id,
                ]
            )->setHidden(['password']);
            if ($userUpdate) {
                $userUpdate->update([
                    'status' => $status,
                    'updated_by' => auth()->user()->user_id,
                    'updated_by_name' => auth()->user()->name,
                ]);
            } else {
                \Log::error('User not found for ID: ' . $query->user_id);
                // Tambahkan penanganan kesalahan jika userUpdate tidak ditemukan
            }

            if ($data['status_approval'] === 'disetujui') {
                $data['is_admin'] = 1;
            }

            $query->update($data);

            return $query;
        }, config('database.deadlock_retry', 3));
    }

    public function countByApprovalStatus($status)
    {
        return $this->repositoryRegistration->countByApprovalStatus($status);
    }

    public function countAllApprovalStatus()
    {
        return [
            'dalam-antrian' => $this->countByApprovalStatus('dalam-antrian'),
            'ditolak' => $this->countByApprovalStatus('ditolak'),
            'disetujui' => $this->countByApprovalStatus('disetujui'),
        ];
    }

    // ====================================================
    // ALL USER
    // ====================================================
    public function countByActiveStatus($status)
    {
        return $this->repositoryUser->countByActiveStatus($status);
    }

    public function countAllActiveStatus()
    {
        return [
            'active' => $this->countByActiveStatus('active'),
            'inactive' => $this->countByActiveStatus('inactive'),
        ];
    }

    /**
     * Get Paginate User
     *
     * @return Paginate
     */
    public function getUserPaginate($selects = '*', $request = null, $filter = [])
    {
        return $this->repositoryUser->getPaginate($selects, $request, $filter);
    }
    /**
     * Create User
     *
     * @return Model
     */
    public function createUser($data)
    {
        if (isset($data['avatar'])) {
            $image = image_helper()->upload(
                file: $data['avatar'],
                path: 'user/avatar',
            );
            $data['avatar'] = $image;
        }

        $result = DB::transaction(function () use ($data) {
            return $this->repositoryUser->create($data);
        }, config('database.deadlock_retry', 3));

        if (! $result && isset($data['avatar'])) {
            image_helper()->delete($data['avatar']);
        }

        return $result;
    }

    /**
     * Find By User
     *
     * @return Collection
     */
    public function findUserBy($id, $selects = '*', $relations = null)
    {
        return $this->repositoryUser->find($id, $selects, $relations);
    }

    /**
     * Update User
     *
     * @return Model
     */
    public function updateUser($id, $data)
    {
        $query = $this->repositoryUser->find($id);

        if (! $query) {
            throw new ServiceErrorException('Data not found', 422);
        }

        $lastLogo = $query['avatar'];
        if (isset($data['avatar'])) {
            $image = image_helper()->upload(
                file: $data['avatar'],
                path: 'user/avatar',
            );
            $data['avatar'] = $image;
        }

        $result = DB::transaction(function () use ($data, $query) {
            return $query->update($data);
        }, config('database.deadlock_retry', 3));

        if (! $result && isset($data['avatar'])) {
            image_helper()->delete($data['avatar']);
        } elseif ($result) {
            image_helper()->delete($lastLogo);
        }

        return $query;
    }

    /**
     * Update User
     *
     * @return Model
     */
    public function deleteUser($id)
    {
        $query = $this->repositoryUser->find($id);

        if (! $query) {
            throw new ServiceErrorException('Data not found', 422);
        }

        return DB::transaction(function () use ($query) {
            return $query->delete();
        }, config('database.deadlock_retry', 3));
    }

    protected function generateMenu($menu, $permissions, $parent_id = null)
    {
        $menByParent = $menu
            ->where('parent_id', $parent_id)
            ->sortBy('order_menu')
            ->all();

        $mymenu = collect();
        foreach ($menByParent as $value) {
            if ($value->type === 'section') {
                $mymenu->push([
                    'sectionTitle' => $value->title,
                ]);

                $menByParent = $menu
                    ->where('parent_id', $value->id)
                    ->sortBy('order_menu')
                    ->where('type', '!=', 'section')
                    ->all();

                foreach ($menByParent as $value) {
                    $permission = $permissions->where('menu_id', $value->id)->values();

                    $value->permissions = $permission->map(function ($value) {
                        return [
                            'menu_id' => $value->menu_id,
                            'name' => $value->name,
                            'action' => $value->action,
                        ];
                    });
                    $value->children = $this->generateMenu($menu, $permissions, $value->id);
                    $mymenu->push($value);
                }
            } else {
                $permission = $permissions->where('menu_id', $value->id)->values();

                $value->permissions = $permission->map(function ($value) {
                    return [
                        'menu_id' => $value->menu_id,
                        'name' => $value->name,
                        'action' => $value->action,
                    ];
                });
                $value->children = $this->generateMenu($menu, $permissions, $value->id);
                $mymenu->push($value);
            }
        }

        return $mymenu;
    }

    protected function generateMenu2($menu, $permissions, $parent_id = null)
    {
        $menByParent = $menu
            ->where('parent_id', $parent_id)
            ->sortBy('order_menu')
            ->all();

        $mymenu = collect();
        foreach ($menByParent as $value) {
            $permission = $permissions->where('menu_id', $value->id)->values();

            $value->permissions = $permission->map(function ($value) {
                return [
                    'menu_id' => $value->menu_id,
                    'name' => $value->name,
                    'action' => $value->action,
                ];
            });
            $value->sub = $this->generateMenu($menu, $permissions, $value->id);
            $mode = 'parent';
            if ($value->parent_id) {
                if ($value->sub?->count() > 0) {
                    $mode = 'sub';
                }

                if ($value->tab?->count() > 0) {
                    $mode = 'tab';
                }
            }
            $value->mode = $mode;
            $mymenu->push($value);
        }

        return $mymenu;
    }
}
