<?php

namespace App\Http\Contracts;

use App\Models\User;

interface UserInterface extends BaseInterface
{
    public function findById(int $id);

    public function save(User $user);

    public function getAgents();
}
