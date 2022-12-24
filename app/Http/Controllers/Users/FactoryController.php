<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FactoryController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['author:factory']);
    }

    public function index()
    {
        return view('factory.main');
    }

    public function create_batch() {
        return view('factory.create_batch');
    }

    public function create_batch_post(Request $request) {
        dd($request);
    }
}
