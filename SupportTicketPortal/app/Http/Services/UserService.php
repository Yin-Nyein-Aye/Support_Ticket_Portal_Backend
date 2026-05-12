<?php

namespace App\Http\Services;

use App\Http\Contracts\UserInterface;

class UserService extends BaseService
{
    public function __construct(UserInterface $userRepository)
    {
        parent::__construct($userRepository);
    }

    public function assignRole($id, $role = 'agent')
    {
        $user = $this->repository->findById($id);
        $user->syncRoles($role);
        $user->organisation_id = null;

        $this->repository->save($user);

        return $user;
    }

    public function getAgents()
    {
        return $this->repository->getAgents();
    }

    public function model()
    {
        return \App\Models\User::class;
    }
}
