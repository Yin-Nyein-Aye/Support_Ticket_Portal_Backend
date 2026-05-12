<?php

namespace App\Http\Services;

use App\Http\Contracts\PermissionInterface;

class PermissionService extends BaseService
{
    public function __construct(PermissionInterface $repository)
    {
        parent::__construct($repository);
    }

    public function model()
    {
        return \Spatie\Permission\Models\Permission::class;
    }
}
