<?php

namespace App\Http\Repositories;

use Spatie\Permission\Models\Role;
use App\Http\Contracts\RoleInterface;

class RoleRepository extends BaseRepository implements RoleInterface
{
    public function __construct(Role $model)
    {
        parent::__construct($model);
    }
}