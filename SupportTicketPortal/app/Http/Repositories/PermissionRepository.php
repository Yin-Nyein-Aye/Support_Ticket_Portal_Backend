<?php

namespace App\Http\Repositories;

use Spatie\Permission\Models\Permission;
use App\Http\Contracts\PermissionInterface;

class PermissionRepository extends BaseRepository implements PermissionInterface
{
    public function __construct(Permission $model)
    {
        parent::__construct($model);
    }
}