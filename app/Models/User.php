<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'email',
        'password',
        'role',
    ];

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function staffProvinces()
    {
        return $this->hasMany(StaffProvince::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}