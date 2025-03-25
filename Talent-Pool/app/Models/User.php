<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'email_verified_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Méthode requise par JWTSubject
    public function getJWTIdentifier()
    {
        return $this->getKey(); // Retourne l'ID de l'utilisateur
    }

    // Méthode requise par JWTSubject
    public function getJWTCustomClaims()
    {
        return [
            'role' => $this->role, // Vous pouvez ajouter des claims personnalisés si nécessaire
            'email' => $this->email
        ];
    }
}
