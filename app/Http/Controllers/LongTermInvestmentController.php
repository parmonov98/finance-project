<?php

namespace App\Http\Controllers;

use App\Models\LongTermInvestment;
use Illuminate\Http\Request;

class LongTermInvestmentController extends Controller
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
     * @param  \App\Models\LongTermInvestment  $longTermInvestment
     * @return \Illuminate\Http\Response
     */
    public function show(LongTermInvestment $longTermInvestment)
    {
        return view('dashboard.long-term-investment.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LongTermInvestment  $longTermInvestment
     * @return \Illuminate\Http\Response
     */
    public function edit(LongTermInvestment $longTermInvestment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LongTermInvestment  $longTermInvestment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LongTermInvestment $longTermInvestment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LongTermInvestment  $longTermInvestment
     * @return \Illuminate\Http\Response
     */
    public function destroy(LongTermInvestment $longTermInvestment)
    {
        //
    }
}
