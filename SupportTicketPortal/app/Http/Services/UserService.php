<?php

namespace App\Http\Services;

use App\Http\Contracts\UserInterface;

class UserService extends BaseService
{
    public function __construct(UserInterface $userRepository)
    {
        parent::__construct($userRepository);
    }

    public function model()
    {
        return \App\Models\User::class;
    }

}
