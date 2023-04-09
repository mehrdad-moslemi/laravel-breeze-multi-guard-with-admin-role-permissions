<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Staff extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guard = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [ 'name' , 'email' , 'password' , 'avatar' , 'is_active'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password' , 'remember_token'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [ 'email_verified_at' => 'datetime'];


    public function scopeSearch($query , $keyword){
        return $query->where('name' , 'LIKE' , "%{$keyword}%")
                    ->orWhere('email' , 'LIKE' , "%{$keyword}%");
    }
    
    public function permissions(){
        return $this->belongsToMany(permission::class, 'permission_staff');
    }

    public function roles(){
        return $this->belongsToMany(Role::class, 'role_staff');
    }

    public function hasPermission($permission){
        return $this->permissions->contains('name' , $permission->name) || $this->hasRole($permission->roles);
    }
    
    public function hasRole($roles){
        return !! $roles->intersect($this->roles)->all();
    }
}
