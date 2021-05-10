<?php

namespace App\Http\Controllers;

use App\Models\home_loan;
use Illuminate\Http\Request;

class HomeLoanController extends Controller
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
     * @param  \App\Models\home_loan  $home_loan
     * @return \Illuminate\Http\Response
     */
    public function show(home_loan $home_loan)
    {
        return view('dashboard.home-loan.view');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\home_loan  $home_loan
     * @return \Illuminate\Http\Response
     */
    public function edit(home_loan $home_loan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\home_loan  $home_loan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, home_loan $home_loan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\home_loan  $home_loan
     * @return \Illuminate\Http\Response
     */
    public function destroy(home_loan $home_loan)
    {
        //
    }
}
