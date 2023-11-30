<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Repositories\Auth\AuthRepositoryImplement;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private AuthRepositoryImplement $authRepositoryImplement;

    public function __construct(AuthRepositoryImplement $authRepositoryImplement)
    {
        $this->authRepositoryImplement = $authRepositoryImplement;
    }

    public function login(AuthRequest $authRequest)
    {
        return $this->authRepositoryImplement->login($authRequest);
    }

    public function register(AuthRequest $authRequest)
    {
        return $this->authRepositoryImplement->register($authRequest);
    }

    public function logout(AuthRequest $authRequest)
    {
        return $this->authRepositoryImplement->logout($authRequest);
    }
}
