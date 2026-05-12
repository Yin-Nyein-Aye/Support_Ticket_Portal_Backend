<?php

namespace App\Http\Repositories;

use App\Http\Contracts\UserInterface;
use App\Models\User;

class UserRepository extends BaseRepository implements UserInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findById($id)
    {
        return User::findOrFail($id);
    }

    public function save(User $user)
    {
        $user->save();

        return $user;
    }

    public function getAgents()
    {
        return User::role('agent')->with('roles')->get();
    }
}
