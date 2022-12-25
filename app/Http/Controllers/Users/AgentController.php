<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DepotController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class AgentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['author:agent']);
    }

    public function index()
    {
        return view('agent.main');
    }

    public function depot_product() {
        $lines = DB::table('ranges')->get();
        $depots = DB::table('depots')->where('owner_id', '=', Auth::user()->id)->get();
        return view('agent.depot_product', compact('lines', 'depots'));
    }

    public function waiting_products() {
        $arr_transfer_batch = [];
        $group_transfer_batch_products = 
                DB::table('waiting_products')
                ->where([
                    ['status', '=', 0], 
                    ['agent_id', '=', Auth::user()->id],
                ])
                ->groupBy('transfer_batch')->get();
        
        foreach($group_transfer_batch_products as $transfer_batch_product) {
            $count_transfer_batch = 
                DB::table('waiting_products')
                ->select('transfer_batch')
                ->where([
                    ['status', '=', 0], 
                    ['agent_id', '=', Auth::user()->id],
                    ['transfer_batch', '=', $transfer_batch_product -> transfer_batch],
                ])
                ->count();
            $arr_temp = ['transfer_batch' => $transfer_batch_product -> transfer_batch, 
                            'range_id' => $transfer_batch_product -> range_id,
                            'quantity' => $count_transfer_batch,
                            'created_at' => $transfer_batch_product -> created_at
                        ];
            array_push($arr_transfer_batch, $arr_temp);
        }
        $depots = DB::table('depots')->where('owner_id', '=', Auth::user()->id)->get();
        return view('agent.waiting_products', compact('arr_transfer_batch', 'depots'));
    }

    public function transfer_to_depot(Request $request) {
        $request->validate(
            [
                'transfer_batch'=>'required|gt:0',
                'depot'=>'required|gte:0',
                'quantity_to_depot'=>'required|gte:0',
                'quantity'=>'required|gte:0',
            ],
            [
                'depot.required'=>'Vui lòng nhập trường này',
                'quantity.required'=>'Vui lòng nhập trường này',
                'transfer_batch.required'=>'Vui lòng nhập trường này',
                'quantity_to_depot.required'=>'Vui lòng nhập số lượng',
                'depot.gte'=>'Vui lòng nhập đúng',
                'transfer_batch.gt'=>'Vui lòng nhập đúng',
                'quantity_to_depot.gte'=>'Số lượng phải lớn hơn 0',
                'quantity.gte'=>'Số lượng phải lớn hơn 0',
            ]
        );

        if($request -> input('quantity') < $request -> input('quantity_to_depot')) {
            return redirect() -> back() -> with(['message' => 'Nhập sai số lượng']);
        }
        
        $transfer_batch_products = DB::table('waiting_products')
                                    ->where([
                                        ['transfer_batch', '=', $request->input('transfer_batch')],
                                        ['status', '=', 0]
                                    ])
                                    ->get();
        
        $n = 0;
        foreach($transfer_batch_products as $transfer_batch_product) {
            if($n >= $request->input('quantity_to_depot')) {
                return redirect() -> back() -> with(['message' => 'Chuyển sp vào kho thành công']);
            }

            $n++;

            DB::table('waiting_products')
            ->where('product_id', '=', $transfer_batch_product->product_id)
            ->update([
                'status' => 1,
            ]);
            DB::table('products')
                ->where('id', '=', $transfer_batch_product->product_id)
                ->update([
                    'depot_id' => $request->input('depot'),
                ]);
        }
    }
}
