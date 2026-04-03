<?php

namespace App\Http\Controllers;

use App\Http\Services\OrganisationService;
use Illuminate\Http\Request;
use App\Http\Resources\OrganisationResource;
use Illuminate\Validation\Rule;

class OrganisationController extends BaseController
{
    public function __construct(OrganisationService $service)
    {
        parent::__construct($service, OrganisationResource::class);
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
        ]);


        $model = $this->service->create($request->only('name'));
        $this->refreshCache();

        return response()->json([
            'status' => 'success',
            'message' => 'Organisation created successfully',
            'data' => new OrganisationResource($model)
        ], 201);
    }
}
