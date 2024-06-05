<?php

namespace App\Helpers;

use App\Models\Auth\AuthToken;
use App\Repositories\Eloquent\Referensi\KlpRepository;
use App\Repositories\Eloquent\User\MenuRepository;
use App\Repositories\Eloquent\User\PermissionRepository;
use App\Repositories\Eloquent\User\RegistrationRepository;
use App\Repositories\Eloquent\User\RoleHasPermissionRepository;
use App\Repositories\Eloquent\User\UserRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTFactory;

class AuthHelper
{
    /**
     * The header name.
     *
     * @var string
     */
    protected $header = 'authorization';

    /**
     * The header prefix.
     *
     * @var string
     */
    protected $prefix = 'bearer';

    public function __construct(
        protected UserRepository $repositoryUser = new UserRepository(),
        protected MenuRepository $repositoryMenu = new MenuRepository(),
        protected RegistrationRepository $repositoryRegistration = new RegistrationRepository(),
        protected PermissionRepository $repositoryPermission = new PermissionRepository(),
        protected RoleHasPermissionRepository $repositoryRoleHasPermission = new RoleHasPermissionRepository(),
        protected KlpRepository $repositoryKlp = new KlpRepository(),
    ) {
    }

    /**
     * Try to parse the token from the request header.
     *
     * @return string|null
     */
    public function bearerToken(Request $request): string | null
    {
        $header = $request->headers->get($this->header) ?: $this->fromAltHeaders($request);

        if ($header) {
            $start = strlen($this->prefix);

            return trim(substr($header, $start));
        }

        return $request->string('token', '');
    }

    /**
     * Hash Token Using SHA-256
     *
     * @param string $token JSON Web Token
     *
     * @return mixed
     */
    public static function hashToken($token)
    {
        return hash('sha256', $token . config('jwt.secret', 'SPIP'));
    }

    public function timeStampTtlToken()
    {
        $minutes = (int) \config('jwt.ttl');
        return Carbon::now()->addMinutes($minutes)->timestamp;
    }

    /**
     * Get auth users data from redis or db using token.
     *
     * @param string $token JSON Web Token
     *
     * @return mixed
     */
    public static function getByToken(string $token)
    {
        try {
            # Hash Token
            $hashToken = self::hashToken($token);

            # Search on redis
            $data = Cache::get($hashToken);
            if ($data) {
                return (array) json_decode($data);
            }

            # Search on db_auth if not exists from redis
            $data = AuthToken::where('token', $hashToken)->firstOrFail();

            return \collect($data)->forget('token')->toArray();
        } catch (\Throwable $th) {
            return null;
        }
    }

    /**
     * Store token to redis.
     *
     * @param mixed $authToken
     * @param string $token JSON Web Token
     * @param int $expired seconds
     *
     * @return mixed
     */
    public function storeAuthToken($params)
    {
        $params['user_agent'] = \request()->header('User-Agent');
        $params['url'] = config('app.url');
        [$token, $payload, $ttl] = self::generateToken($params);

        $hashToken = self::hashToken($token);

        $data = [
            ...$payload['data'],
            ...$payload['fa_payload'],
            'expires_at' => $payload['exp'],
        ];
        # Cached token into redis
        Cache::put($hashToken, json_encode($data), (int) ($ttl * 60));
        # START STORE TOKEN
        AuthToken::create([
            'user_id' => $params['user_id'],
            'fullname' => $params['fullname'],
            'email' => $params['email'],
            'token' => $hashToken,
            'expires_at' => $payload['exp'],
        ]);

        $oldAuthToken = AuthToken::where([
            'user_id' => $params['user_id'],
            ['expires_at', '<', $payload['exp']],
        ])->get();

        foreach ($oldAuthToken as $index => $data) {
            Cache::forget($data->token);
            AuthToken::where([
                'token' => $data->token,
            ])->delete();
        }
        # END STORE TOKEN
        return $token;
    }

    /**
     * Delete auth users data from redis or db using token.
     *
     * @param string $token JSON Web Token
     * @param array $data
     *
     * @return bool
     */
    public function destroyToken($data): bool
    {
        try {
            # Destroy token from redis
            Cache::store('redis_auth')->forget($data['token']);

            # Destroy token from db_auth

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Generate refresh Token
     *
     * @return string
     */
    public function generateRefreshToken($user_id): string
    {
        $loginRequest['user_agent'] = \request()->header('User-Agent');

        $ttl = \config('jwt.refresh_ttl');
        $data = [
            'token_type' => 'refresh_token',
            'user_id' => $user_id,
        ];

        $payload = JWTFactory::sub($user_id)
            ->setDefaultClaims(['iat', 'exp', 'nbf', 'jti'])
            ->iss($loginRequest['url'])
            ->data($data)
            ->setTtl($ttl)
            ->make();

        return JWTAuth::encode($payload)->get();
    }

    /**
     * Generate ttl refresh Token
     *
     * @return string
     */
    public function generateTtlRefreshToken(): string
    {
        return Carbon::now()->addMinute(\config('jwt.refresh_ttl'))->timestamp;
    }

    public function getUserProfile()
    {
        $userId = auth()->user()->user_id;
        $user = $this->repositoryUser->findBy(
            key: [
                'id' => $userId,
            ]
        )->setHidden(['password']);

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

        return $user;
    }

    /**
     * Attempt to parse the token from some other possible headers.
     *
     * @return string|null
     */
    protected function fromAltHeaders(Request $request)
    {
        return $request->server->get('HTTP_AUTHORIZATION') ?: $request->server->get('REDIRECT_HTTP_AUTHORIZATION');
    }

    /**
     * Generating Token
     *
     * @param array $loginRequest
     *
     * @return array
     */
    protected static function generateToken(array $loginRequest): array
    {
        $ttl = (int) \config('jwt.ttl');

        $data = [
            'token_type' => 'access_token',
            'user_id' => $loginRequest['user_id'],
            'fullname' => $loginRequest['fullname'],
            'email' => $loginRequest['email'],
        ];

        $fa_payload = [
            'fa_step' => false,
            'fa_status' => false,
            'fa_valid' => false,
        ];

        $payload = JWTFactory::sub($loginRequest['user_id'])
            ->setDefaultClaims(['iat', 'exp', 'nbf', 'jti'])
            ->iss($loginRequest['url'])
            ->data($data)
            ->fa_payload($fa_payload)
            ->setTtl($ttl)
            ->make();
        $token = JWTAuth::encode($payload)->get();

        return [$token, $payload, $ttl];
    }
}
