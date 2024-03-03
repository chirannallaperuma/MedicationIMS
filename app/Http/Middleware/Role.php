<?php

namespace App\Http\Middleware;

use App\Traits\ApiHelperTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Role
{
    use ApiHelperTrait;

    /**
     * handle
     *
     * @param  mixed $request
     * @param  mixed $next
     * @param  mixed $role
     * @return void
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!$request->user()) {
            abort($this->apiError('Unauthorized', Response::HTTP_UNAUTHORIZED));
        }

        // Retrieve user's role
        $userRole = $request->user()->role;

        // Check if user's role matches any of the specified roles
        if (!in_array($userRole, $roles)) {
            abort($this->apiError('Unauthorized', Response::HTTP_UNAUTHORIZED));
        }

        // Allow or deny access to specific functions based on the user's role
        switch ($userRole) {
            case 'owner':
                // Allow access to all functions for owner
                break;
            case 'manager':
                // Allow access to update and delete functions for manager
                if ($request->isMethod('GET')) {
                    // Allow access to get function
                    break;
                } elseif ($request->isMethod('DELETE')) {
                    // Allow access to delete function
                    break;
                } elseif ($request->isMethod('PUT')) {
                    // Allow access to update function
                    break;
                } else {
                    abort($this->apiError('Unauthorized', Response::HTTP_UNAUTHORIZED));
                }
            case 'cashier':
                // Allow access to update function for cashier
                if ($request->isMethod('PUT')) {
                    // Allow access to update function
                    break;
                } elseif ($request->isMethod('GET')) {
                    // Allow access to delete function
                    break;
                } else {
                    abort($this->apiError('Unauthorized', Response::HTTP_UNAUTHORIZED));
                }
            default:
                abort($this->apiError('Unauthorized', Response::HTTP_UNAUTHORIZED));
        }

        return $next($request);
    }
}
