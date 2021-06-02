<?php

namespace App\Http\Controllers;

use App\Models\SuperData;
use Illuminate\Http\Request;

class SuperDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SuperData  $superData
     * @return \Illuminate\Http\Response
     */
    public function show(SuperData $superData)
    {
        return view('dashboard.program-super.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SuperData  $superData
     * @return \Illuminate\Http\Response
     */
    public function edit(SuperData $superData)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SuperData  $superData
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SuperData $superData)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SuperData  $superData
     * @return \Illuminate\Http\Response
     */
    public function destroy(SuperData $superData)
    {
        //
    }
}
