<?php

namespace App\Repositories;

use App\Models\MedicationModel;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\MedicationRepositoryInterface;

class DbMedicationRepository extends BaseRepository implements MedicationRepositoryInterface
{
    public function __construct(MedicationModel $model)
    {
        $this->model = $model;
    }
}
