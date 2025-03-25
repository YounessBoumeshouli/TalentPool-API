<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    protected $fillable = ['name', 'email', 'password', 'role'];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'recruiter_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'candidate_id');
    }
}
