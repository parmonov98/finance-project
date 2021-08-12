<?php

namespace App\Http\Controllers;

use App\Models\ProgramPay;
use Illuminate\Http\Request;

class ProgramPayController extends Controller
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
     * @param  \App\Models\ProgramPay  $programPay
     * @return \Illuminate\Http\Response
     */
    public function show(ProgramPay $programPay)
    {
        return view('dashboard.program-pay.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProgramPay  $programPay
     * @return \Illuminate\Http\Response
     */
    public function edit(ProgramPay $programPay)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProgramPay  $programPay
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProgramPay $programPay)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProgramPay  $programPay
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProgramPay $programPay)
    {
        //
    }
}
