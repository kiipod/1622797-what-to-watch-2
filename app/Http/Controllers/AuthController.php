<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use app\Http\Responses\Success;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Success
     */
    public function login(Request $request)
    {
        return new Success();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Success
     */
    public function logout()
    {
        return new Success();
    }
}
