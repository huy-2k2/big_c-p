<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['author:admin']);
    }

    public function index()
    {
        return 'trang chủ admin';
    }
}
