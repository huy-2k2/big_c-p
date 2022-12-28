<?php

namespace App\Http\Controllers;

use App\Models\WaitingProduct;
use App\Http\Requests\StoreWaitingProductRequest;
use App\Http\Requests\UpdateWaitingProductRequest;

class WaitingProductController extends Controller
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
     * @param  \App\Http\Requests\StoreWaitingProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWaitingProductRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WaitingProduct  $waitingProduct
     * @return \Illuminate\Http\Response
     */
    public function show(WaitingProduct $waitingProduct)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WaitingProduct  $waitingProduct
     * @return \Illuminate\Http\Response
     */
    public function edit(WaitingProduct $waitingProduct)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateWaitingProductRequest  $request
     * @param  \App\Models\WaitingProduct  $waitingProduct
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateWaitingProductRequest $request, WaitingProduct $waitingProduct)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WaitingProduct  $waitingProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(WaitingProduct $waitingProduct)
    {
        //
    }
}
