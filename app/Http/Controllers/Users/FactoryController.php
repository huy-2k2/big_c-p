<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\BatchController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DepotController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FactoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['author:factory']);
    }

    public function index()
    {
        return view('factory.main');
    }

    public function create_batch() {
        $ranges = DB::table('ranges')->get();
        $depots = DB::table('depots')->where('owner_id', Auth::user()->id)->get();
        
        return view('factory.create_batch', compact('ranges', 'depots'));
    }

    public function create_batch_post(Request $request) {
        $temp = BatchController::create_batch($request, Auth::user()->id);
        if(!$temp) {
            return redirect()->route('home');
        } else {
            return redirect()->route('factory.create_batch');
        }
    }

    //depot
    public function factory_depots() {
        $depots = DepotController::get_all_depots(Auth::user()->id);
    
        return view('factory.factory_depots', compact('depots'));
    }

    public function add_factory_depot() {
        return view('factory.add_factory_depot');
    }

    public function post_add_factory_depot(Request $request) {
        DepotController::add_depot($request, Auth::user()->id);

        return redirect()->route('factory.factory_depots');
    }

    public function delete_factory_depot($id) {
        DepotController::delete_depot($id); 
        return redirect()->route('factory.factory_depots');
    }

    public function edit_factory_depot($id) {
        $depot_edit = DB::table('depots')->where('id', '=', $id)->get()->first();
        if($depot_edit) {
            return view('factory.edit_factory_depot', compact('depot_edit'));
        } else {
            return redirect()->route('factory.factory_depots');
        }
    }

    public function put_edit_factory_depot($id, Request $request) {
        DepotController::edit_depot($id, $request); 

        return redirect()->route('factory.factory_depots');
    }
}
