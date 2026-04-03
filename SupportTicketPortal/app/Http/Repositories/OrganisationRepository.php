<?php

namespace App\Http\Repositories;

use App\Http\Repositories\BaseRepository;
use App\Models\Organisation;

class OrganisationRepository extends BaseRepository
{
    public function __construct(Organisation $model)
    {
        parent::__construct($model);
    }
}
