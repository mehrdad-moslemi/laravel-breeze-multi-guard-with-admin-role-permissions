<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = ['name' , 'label'];

    public function user(){
        return $this->belongsToMany(Staff::class, 'permission_staff');
    }

    public function roles(){
        return $this->belongsToMany(Role::class, 'permission_role');
    }
}
