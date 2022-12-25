<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\BatchController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DepotController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

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
            return redirect()->route('factory.depot_product', ['result' => false]);
        } else {
            return redirect()->route('factory.depot_product', ['result' => true]);
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

    public function depot_product() {
        $lines = DB::table('ranges')->get();
        $depots = DB::table('depots')->where('owner_id', '=', Auth::user()->id)->get();
        
        return view('factory.depot_product', compact('lines', 'depots'));
    }

    public function transfer_prod_to_agent() {
        $lines = DB::table('ranges')->get();
        $agents = DB::table('agents')->get();
        return view('factory.transfer_prod_to_agent', compact('lines', 'agents'));
    }

    public function post_transfer_prod_to_agent(Request $request) {
        $request->validate(
            [
                'quantity_prod'=>'required|gt:0',
                'range'=>'required|gte:0',
                'agent'=>'required|gte:0',
            ],
            [
                'range.required'=>'Vui lòng nhập trường này',
                'quantity_prod.required'=>'Vui lòng nhập trường này',
                'agent.required'=>'Vui lòng nhập trường này',
                'range.gte'=>'Vui lòng nhập đúng',
                'quantity_prod.gt'=>'Số lượng phải lớn hơn 0',
                'agent.gte'=>'Vui lòng nhập đúng',
            ]
        );

        $count_prod_in_depot = Product::count_quantity_product(['range_id', 'factory_id', 'status_id'], [$request->input('range'), Auth::user() -> id, 1]);
        if($count_prod_in_depot >= $request->input('quantity_prod')) {
            $all_prod_in_depot = Product::get_product(['range_id', 'factory_id', 'status_id'], [$request->input('range'), Auth::user() -> id, 1]);
            $i = 0;
            foreach($all_prod_in_depot as $prod) {
                if ($i == $request->input('quantity_prod')) { 
                    return redirect() -> route('factory.transfer_prod_to_agent', ['result' => true]);
                }

                $i++;

                DB::table('products')->where('id', $prod->id)->update([
                    'depot_id' => 8, //cần sửa
                    'agent_id' => $request->input('agent'),
                    'status_id' => 2,
                    'owner_id' => $request->input('agent')
                ]);
            }

            return redirect() -> route('factory.transfer_prod_to_agent', ['result' => true]);
        } else {
            return redirect() -> route('factory.transfer_prod_to_agent', ['result' => false]);
        }
    }
}

    

