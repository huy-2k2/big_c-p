<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Http\Requests\StoreBatchRequest;
use App\Http\Requests\UpdateBatchRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Product;
use App\Models\Depot;

class BatchController extends Controller
{
    public static function create_batch(Request $request, $owner_id) {
        $request->validate(
            [
                'range'=>'required|gte:0',
                'quantity'=>'required|gt:0',
                'depot'=>'required|gte:0',
            ],
            [
                'range.required'=>'Vui lòng nhập trường này',
                'quantity.required'=>'Vui lòng nhập trường này',
                'depot.required'=>'Vui lòng nhập trường này',
                'range.gte'=>'Vui lòng nhập đúng',
                'quantity.gt'=>'Số lượng phải lớn hơn 0',
                'depot.gte'=>'Vui lòng nhập đúng',
            ]
        );
        
        $range = DB::table('ranges')->where('id', '=', $request->input('range'))->get()->first();
        
        if($range) {
            
            $is_true_depot = Depot::depot_check_have($owner_id, $request->input('depot'));

            if($is_true_depot) {
                $still_empty = Depot::depot_check_still_empty($request->input('depot'), $request->input('quantity'));
                
                if(!$still_empty) {
                    return false;
                }

                $current_batch_id = DB::table('batches')->insertGetId([
                    'quantity' => $request->input('quantity'),
                    'range_id' => $request->input('range'),
                    'factory_id' => $owner_id,
                    'status_id' => 1,
                    'manufacturing_date' => Carbon::now()
                ]);

                for($i = 0; $i < $request->input('quantity'); $i++) {
                    DB::table('products')->insert([
                        'batch_id' => $current_batch_id,
                        'depot_id' => $request->input('depot'),
                        'agent_id' => null,                        
                        'factory_id' => $owner_id,
                        'warranty_count' => 0,
                        'warranty_id' => null,
                        'customer_buy_time' => null,
                        'customer_id' => null,
                        'status_id' => 1,
                        'owner_id' => $owner_id,
                        'range_id' => $request->input('range'),
                        'created_at' => Carbon::now(),
                    ]);
                }

                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
