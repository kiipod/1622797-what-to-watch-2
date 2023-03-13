<?php

namespace App\Http\Controllers;

use App\Http\Responses\Success;
use Illuminate\Http\Request;

class FilmController extends Controller
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Success
     */
    public function show($id)
    {
        return new Success();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Success
     */
    public function update(Request $request, $id)
    {
        return new Success();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Success
     */
    public function getSimilar($id)
    {
        return new Success();
    }
}
