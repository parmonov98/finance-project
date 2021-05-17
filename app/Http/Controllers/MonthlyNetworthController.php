<?php

namespace App\Http\Controllers;

use App\Models\MonthlyNetworth;
use Illuminate\Http\Request;

class MonthlyNetworthController extends Controller
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
     * @param  \App\Models\MonthlyNetworth  $monthlyNetworth
     * @return \Illuminate\Http\Response
     */
    public function show(MonthlyNetworth $monthlyNetworth)
    {
        return view('dashboard.monthly-networth.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MonthlyNetworth  $monthlyNetworth
     * @return \Illuminate\Http\Response
     */
    public function edit(MonthlyNetworth $monthlyNetworth)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MonthlyNetworth  $monthlyNetworth
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MonthlyNetworth $monthlyNetworth)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MonthlyNetworth  $monthlyNetworth
     * @return \Illuminate\Http\Response
     */
    public function destroy(MonthlyNetworth $monthlyNetworth)
    {
        //
    }
}
