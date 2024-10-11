<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Show user details.
     *
     */
    public function show(Request $request)
    {
        // Check if the user is authenticated
        $user = $request->user('api');

        if ($user) {
            // If the token is valid and the user is authenticated, return user data
            return $this->jsonResponse([
                'user' => $user,
            ], 200);
        }

        // If the user is not authenticated, return an error response
        return $this->jsonResponse([
            'error' => 'Unauthorized or invalid token',
        ], 401);
    }
}
