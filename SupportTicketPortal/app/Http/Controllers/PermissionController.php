<?php

namespace App\Http\Controllers;

use App\Http\Services\PermissionService;
use App\Http\Resources\V1\PermissionResource;
use Illuminate\Http\Request;

class PermissionController extends BaseController
{
    public function __construct(PermissionService $service)
    {
        parent::__construct($service, PermissionResource::class, ['roles']);
    }

    // CREATE
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name',
            'guard_name' => 'nullable|string'
        ]);

        return parent::store($request);
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name,' . $id,
            'guard_name' => 'nullable|string'
        ]);

        return parent::update($request, $id);
    }
}