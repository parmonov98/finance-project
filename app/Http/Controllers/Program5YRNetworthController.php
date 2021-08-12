<?php

namespace App\Http\Controllers;

use App\Models\Program5YRNetworth;
use Illuminate\Http\Request;

class Program5YRNetworthController extends Controller
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
     * @param  \App\Models\Program5YRNetworth  $program5YRNetworth
     * @return \Illuminate\Http\Response
     */
    public function show(Program5YRNetworth $program5YRNetworth)
    {
        return view('dashboard.5yr-networth.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Program5YRNetworth  $program5YRNetworth
     * @return \Illuminate\Http\Response
     */
    public function edit(Program5YRNetworth $program5YRNetworth)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Program5YRNetworth  $program5YRNetworth
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Program5YRNetworth $program5YRNetworth)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Program5YRNetworth  $program5YRNetworth
     * @return \Illuminate\Http\Response
     */
    public function destroy(Program5YRNetworth $program5YRNetworth)
    {
        //
    }
}
