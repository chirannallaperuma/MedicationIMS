<?php

namespace App\Providers;

use App\Repositories\Contracts\CustomerRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\DbCustomerRepository;
use App\Repositories\DbUserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * register
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, DbUserRepository::class);
        $this->app->bind(CustomerRepositoryInterface::class, DbCustomerRepository::class);
    }

    /**
     * boot
     *
     * @return void
     */
    public function boot()
    {
    }
}
