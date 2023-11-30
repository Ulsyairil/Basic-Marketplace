<?php

namespace App\Repositories\Auth;

use LaravelEasyRepository\Repository;

interface AuthRepository extends Repository
{
    public function login($request);
    public function register($request);
    public function logout($request);
}
