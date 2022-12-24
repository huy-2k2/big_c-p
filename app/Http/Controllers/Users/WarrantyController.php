<?php

namespace App\Http\Controllers;

use App\Models\Warranty;
use App\Http\Requests\StoreWarrantyRequest;
use App\Http\Requests\UpdateWarrantyRequest;

class WarrantyController extends Controller
{
    public function __construct()
    {
        $this->middleware(['author:warranty']);
    }

    public function index()
    {
        return view('warranty.main');
    }
}
