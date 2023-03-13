<?php

namespace App\Http\Controllers;

use App\Http\Responses\Success;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /**
     * @return Success
     */
    public function register(Request $request)
    {
        return new Success();
    }
}
