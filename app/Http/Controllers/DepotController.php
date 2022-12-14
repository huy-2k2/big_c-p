<?php

namespace App\Http\Controllers;

use App\Models\Depot;
use App\Http\Requests\StoreDepotRequest;
use App\Http\Requests\UpdateDepotRequest;
use App\Rules\DepotSizeAcceptable;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DepotController extends Controller
{
    public static function get_all_depots($owner_id)
    {
        return Depot::where('owner_id', $owner_id)->get();
    }

    public static function add_depot(Request $request, $owner_id)
    {
        $request->validate(
            [
                'depot_name' => 'required',
                'size' => 'required|gt:0',

            ],
            [
                'depot_name.required' => 'Vui lòng nhập trường này',
                'size.gt' => 'Kích thước kho phải lớn hơn 0',
                'size.required' => 'Vui lòng nhập trường này',
            ]
        );

        DB::table('depots')->insert([
            'depot_name' => $request->input('depot_name'),
            'size' => $request->input('size'),
            'status_b' => true,
            'owner_id' => $owner_id
        ]);
    }

    public static function delete_depot($id)
    {
        $depot_delete = DB::table('depots')->where('id', '=', $id)->get();
        if ($depot_delete->first()) {
            DB::table('depots')->where('id', '=', $id)->delete();
        }
    }

    public static function edit_depot(Request $request, $status_id = 1)
    {
        $request->validate(
            [
                'depot_name' => 'required',
                'size' => ['required', 'gt:0', new DepotSizeAcceptable($request->depot_id, $request->size, $status_id)],
            ],
        );

        DB::table('depots')->where('id', $request->depot_id)->update([
            'depot_name' => $request->depot_name,
            'size' => $request->size,
        ]);
    }
}
