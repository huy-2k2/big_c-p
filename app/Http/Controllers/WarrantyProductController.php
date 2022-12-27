<?php

namespace App\Http\Controllers;

use App\Models\WarrantyProduct;
use App\Http\Requests\StoreWarrantyProductRequest;
use App\Http\Requests\UpdateWarrantyProductRequest;

class WarrantyProductController extends Controller
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
     * @param  \App\Http\Requests\StoreWarrantyProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWarrantyProductRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WarrantyProduct  $warrantyProduct
     * @return \Illuminate\Http\Response
     */
    public function show(WarrantyProduct $warrantyProduct)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WarrantyProduct  $warrantyProduct
     * @return \Illuminate\Http\Response
     */
    public function edit(WarrantyProduct $warrantyProduct)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateWarrantyProductRequest  $request
     * @param  \App\Models\WarrantyProduct  $warrantyProduct
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateWarrantyProductRequest $request, WarrantyProduct $warrantyProduct)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WarrantyProduct  $warrantyProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(WarrantyProduct $warrantyProduct)
    {
        //
    }
}
