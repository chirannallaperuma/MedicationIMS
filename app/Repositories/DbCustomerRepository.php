<?php

namespace App\Repositories;

use App\Models\CustomerModel;
use App\Repositories\Contracts\CustomerRepositoryInterface;

class DbCustomerRepository extends BaseRepository implements CustomerRepositoryInterface
{
    public function __construct(CustomerModel $model)
    {
        $this->model = $model;
    }
}
