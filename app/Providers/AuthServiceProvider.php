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

        if (Schema::hasTable('permissions')) {
            foreach(Permission::all() as $permission){
                Gate::define($permission->name , function($user) use ($permission){
                    return $user->hasPermission($permission);
                });
            }
        }

        ResetPassword::createUrlUsing(function (Staff $staff, string $token) {
            if(request()->fullUrlIs(route('admin.index') . "*")){
                return route('admin.password.reset' , $token);
            }else{
                return route('password.reset' , $token);
            }
        });
    }
}
