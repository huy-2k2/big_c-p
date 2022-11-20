<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WarrantyCenterController extends Controller
{
    public function __construct()
    {
        $this->middleware(['author:warranty_center']);
    }

    public function index()
    {
        return view('warranty_center.main');
    }
}
