<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    public function createUser(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role']
        ]);
    }

    public function generateToken(User $user)
    {
        return JWTAuth::fromUser($user);
    }

    public function validateCredentials($email, $password)
    {
        $user = User::where('email', $email)->first();
        return $user && Hash::check($password, $user->password);
    }
}
