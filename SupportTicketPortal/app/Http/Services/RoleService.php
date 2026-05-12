<?php

namespace App\Http\Services;

use App\Http\Contracts\RoleInterface;

class RoleService extends BaseService
{
    public function __construct(RoleInterface $roleRepository)
    {
        parent::__construct($roleRepository);
    }

    public function model()
    {
        return \Spatie\Permission\Models\Role::class;
    }
}
