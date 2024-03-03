<?php

namespace App\Http\Middleware;

use App\Traits\ApiHelperTrait;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Response;

class Authenticate extends Middleware
{
    use ApiHelperTrait;
    
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }

    /**
     * unauthenticated
     *
     * @param  mixed $request
     * @param  mixed $guards
     * @return void
     */
    protected function unauthenticated($request, array $guards)
    {
        abort($this->apiError('Unauthenticated', Response::HTTP_UNAUTHORIZED));
    }
}
