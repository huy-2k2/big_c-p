<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FactoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['author:factory']);
    }

    public function index()
    {
        return 'trang chủ factory';
    }
}
