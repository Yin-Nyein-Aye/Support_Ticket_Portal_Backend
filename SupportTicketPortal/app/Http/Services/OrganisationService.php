<?php

namespace App\Http\Services;

use App\Http\Services\BaseService;
use App\Http\Repositories\OrganisationRepository;
use App\Models\Organisation; // ← you need this import

class OrganisationService extends BaseService
{
    public function __construct(OrganisationRepository $repository)
    {
        parent::__construct($repository);
    }

    public function model(): string
    {
        return Organisation::class;
    }

    public function create(array $data)
    {
        // Remove non-letters and uppercase
        $cleanName = strtoupper(preg_replace('/[^A-Za-z]/', '', $data['name']));

        // Take first 3 letters OR full if less than 3
        $prefix = strlen($cleanName) >= 3
            ? substr($cleanName, 0, 3)
            : $cleanName;

        // Generate unique org_code
        do {
            $randomNumber = str_pad(rand(0, 9999999), 7, '0', STR_PAD_LEFT);
            $orgCode = $prefix . $randomNumber;
        } while (Organisation::where('org_code', $orgCode)->exists());

        $data['org_code'] = $orgCode;
        $data['is_active'] = true;

        return $this->repository->create($data);
    }
}
