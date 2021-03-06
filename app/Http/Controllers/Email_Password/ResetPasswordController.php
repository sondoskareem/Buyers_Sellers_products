<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends ApiController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;
    protected function sendResetResponse(Request $request, $response)
    {
        return $this->sucessResponse($response , 200);

    }

   
    protected function sendResetFailedResponse(Request $request, $response)
    {
        return $this->errorResponse($response , 422);
    }

}
