<?php

namespace App\Http\Controllers;

use App\Models\InvestPersonal;
use Illuminate\Http\Request;

class InvestPersonalController extends Controller
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
     * @param  \App\Models\InvestPersonal  $investPersonal
     * @return \Illuminate\Http\Response
     */
    public function show(InvestPersonal $investPersonal)
    {
        return view('dashboard.invest-personal.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InvestPersonal  $investPersonal
     * @return \Illuminate\Http\Response
     */
    public function edit(InvestPersonal $investPersonal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InvestPersonal  $investPersonal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InvestPersonal $investPersonal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InvestPersonal  $investPersonal
     * @return \Illuminate\Http\Response
     */
    public function destroy(InvestPersonal $investPersonal)
    {
        //
    }
}
