<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name' , 'label'];

    public function user(){
        return $this->belongsToMany(Staff::class, 'role_staff');
    }

    public function permissions(){
        return $this->belongsToMany(Permission::class, 'permission_role');
    }
}
