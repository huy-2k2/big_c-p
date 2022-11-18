<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function __construct()
    {
        $this->middleware(['author:vendor']);
    }

    public function index()
    {
        return 'trang chủ vendor';
    }
}
