<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use app\Http\Responses\Success;

class CommentController extends Controller
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
    public function destroy($id)
    {
        return new Success();
    }
}
