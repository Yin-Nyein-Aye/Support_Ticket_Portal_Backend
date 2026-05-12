<?php

namespace App\Http\Repositories;

use App\Http\Contracts\RoleInterface;
use Spatie\Permission\Models\Role;

class RoleRepository extends BaseRepository implements RoleInterface
{
    public function __construct(Role $model)
    {
        parent::__construct($model);
    }
}
