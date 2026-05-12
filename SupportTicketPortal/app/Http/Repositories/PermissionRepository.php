<?php

namespace App\Http\Repositories;

use App\Http\Contracts\PermissionInterface;
use Spatie\Permission\Models\Permission;

class PermissionRepository extends BaseRepository implements PermissionInterface
{
    public function __construct(Permission $model)
    {
        parent::__construct($model);
    }
}
