<?php

namespace App\Providers;

use App\Auth\Auth;
use App\Auth\Exceptions\InvalidJWTHandler;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $this->registerAuth();
    }

    /**
     * Registers auth binding users.
     *
     * @return void
     */
    protected function registerAuth()
    {
        foreach ([null, 'api'] as $guard) {
            $guardType = is_null($guard) ? '' : $guard;
            FacadesAuth::viaRequest($guardType, function (Request $request) use ($guard) {
                $auth = new Auth($guard);

                if (! $auth->check($guard, is_null($guard))) {
                    throw new InvalidJWTHandler(
                        errors: 'Tidak ada otorisasi'
                    );
                }

                return $auth;
            });
        }
    }
}
