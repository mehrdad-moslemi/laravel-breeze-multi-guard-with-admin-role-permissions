<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\Staff;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
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

        try {
            if (Schema::hasTable('permissions')) {
                foreach(Permission::all() as $permission){
                    Gate::define($permission->name , function($user) use ($permission){
                        return $user->hasPermission($permission);
                    });
                }
            }
        } catch (\Exception $e) {
            //throw $th;
        }

        try {
            ResetPassword::createUrlUsing(function ($user, string $token) {
                if(request()->fullUrlIs(route('admin.index') . "*")){
                    return route('admin.password.reset' , ['token' => $token , 'email' => $user->email]);
                }else{
                    return route('password.reset' , ['token' => $token , 'email' => $user->email]);
                }
            });
        } catch (\Exception $e) {
            //throw $th;
        }
    }
}
