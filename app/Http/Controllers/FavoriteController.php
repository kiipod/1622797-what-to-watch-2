<?php

namespace App\Http\Controllers;

use App\Http\Responses\Success;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Success
     */
    public function index()
    {
        return new Success();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Success
     */
    public function store(Request $request)
    {
        return new Success();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Success
     */
    public function destroy($id)
    {
        return new Success();
    }
}
