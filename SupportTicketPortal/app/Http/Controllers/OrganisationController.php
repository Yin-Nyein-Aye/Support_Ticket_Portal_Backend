<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrganisationResource;
use App\Http\Services\OrganisationService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrganisationController extends BaseController
{
    public function __construct(OrganisationService $service)
    {
        parent::__construct($service, OrganisationResource::class, ['users']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('organisations', 'name'),
            ],
        ], [
            'name.required' => 'Organisation name is required.',
            'name.string' => 'Organisation name must be a valid string.',
            'name.max' => 'Organisation name cannot exceed 150 characters.',
            'name.unique' => 'This organisation name is already taken.',
        ]);

        $model = $this->service->create($request->only('name'));
        $this->refreshCache();

        return response()->json([
            'status' => 'success',
            'message' => 'Organisation created successfully',
            'data' => new OrganisationResource($model),
        ], 201);
    }
}
