<?php

namespace App\Http\Controllers;

use App\Models\Warranty;
use App\Http\Requests\StoreWarrantyRequest;
use App\Http\Requests\UpdateWarrantyRequest;

class WarrantyController extends Controller
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
     * @param  \App\Http\Requests\StoreWarrantyRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWarrantyRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Warranty  $warranty
     * @return \Illuminate\Http\Response
     */
    public function show(Warranty $warranty)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Warranty  $warranty
     * @return \Illuminate\Http\Response
     */
    public function edit(Warranty $warranty)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateWarrantyRequest  $request
     * @param  \App\Models\Warranty  $warranty
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateWarrantyRequest $request, Warranty $warranty)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Warranty  $warranty
     * @return \Illuminate\Http\Response
     */
    public function destroy(Warranty $warranty)
    {
        //
    }
}
