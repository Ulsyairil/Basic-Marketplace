<?php

namespace App\Repositories\Auth;

use F9Web\ApiResponseHelpers;
use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthRepositoryImplement extends Eloquent implements AuthRepository
{

    use ApiResponseHelpers;

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property User|mixed $model;
     */
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function login($request)
    {
        $user = $this->model->query()->where('email', $request->email)->first();

        if ($user->email_verified_at == null) {
            return $this->respondUnAuthenticated("Email Not Verified");
        }

        if (!Hash::check($request->password, $user->password)) {
            return $this->respondUnAuthenticated("Wrong Password");
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->respondWithSuccess([
            'message' => 'Login success',
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    public function register($request)
    {
        $user = $this->model->query()->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->confirm_password),
            'roles' => $request->roles
        ]);

        return $this->respondWithSuccess([
            "message" => "Account succesfully created",
            "data"  => $user
        ]);
    }

    public function logout($request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->respondOk("Logout success");
    }
}
