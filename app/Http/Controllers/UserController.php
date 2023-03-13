<?php

namespace App\Http\Controllers;

use App\Http\Responses\Success;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @return Success
     */
    public function index()
    {
        return new Success();
    }

    /**
     * @param Request $request
     * @return Success
     */
    public function update(Request $request)
    {
        return new Success();
    }
}
