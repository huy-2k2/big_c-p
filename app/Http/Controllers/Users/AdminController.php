<?php

namespace App\Http\Controllers\Users;

use App\Events\CreateNotifiEvent;
use App\Exports\ExcelsExport;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Product;
use App\Models\Range;
use App\Models\Status;
use App\Models\Warranty;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;


class AdminController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.product_line');
    }

    public function create_notifi()
    {
        $users = User::all();
        $agents = User::get_users_with_role('agent');
        $warranties = User::get_users_with_role('warranty');
        $factories = User::get_users_with_role('factory');
        return view('admin.create_notifi', ['users' => $users, 'agents' => $agents, 'warranties' => $warranties, 'factories' => $factories]);
    }

    public function store_notifi(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'min:8'],
            'content' => ['required', 'string', 'min:16'],
        ]);
        if (!$request->has('agent') && !$request->has('factory') && !$request->has('warranty')) {
            return back()->withInput();
        }

        $notification = Notification::create([
            'title' => $request->title,
            'content' => $request->content
        ]);

        $datas_users = [
            ['role' => 'factory', 'ids' => $request->factory ?? []],
            ['role' => 'warranty', 'ids' => $request->warranty ?? []],
            ['role' => 'agent', 'ids' => $request->agent ?? []],
        ];

        foreach ($datas_users as $data_users) {
            foreach ($data_users['ids'] as $id) {
                if ($id == 0) {
                    $users = User::get_users_with_role($data_users['role']);
                    foreach ($users as $user) {
                        $notification->users()->attach($user->id);
                        broadcast(new CreateNotifiEvent(['user_id' => $user->id, 'notification' => $notification, 'time' => $notification->created_at->toDateTimeString()]));
                    }   
                } else {
                    $notification->users()->attach($id);
                    broadcast(new CreateNotifiEvent(['user_id' => $id, 'notification' => $notification, 'time' => $notification->created_at->toDateTimeString()]));
                }
            }
        }

        return Redirect::back()->with(['message' => 'tạo thông báo thành công']);
    }

    public function notifi()
    {
        $users = User::all();
        $notifications = Notification::all();
        return view('admin.notifi', ['users' => $users, 'notifications' => $notifications]);
    }

    public function accept_user()
    {
        $users = User::all();
        return view('admin.accept_user', ['users' => $users]);
    }

    //api
    public function accept_user_store(Request $request)
    {
        $user = User::find($request->user_accept_id);

        if (!$user->account_accepted_at) {
            $user->update(['account_accepted_at' => Carbon::now()]);

            if ($user->role_id == 2 /*factory*/) {
                DB::table('factories')->insert(['user_id' => $user->id]);
            } else if ($user->role_id == 3 /*warranty*/) {
                DB::table('warranties')->insert(['user_id' => $user->id]);
            } else if ($user->role_id == 4 /*agent*/) {
                DB::table('agents')->insert(['user_id' => $user->id]);
            } else if ($user->role_id == 5 /*customer*/) {
                DB::table('customers')->insert(['user_id' => $user->id]);
            }
        }

        return response()->json(true);
    }

    //api
    public function accept_user_remove(Request $request)
    {
        $user = User::find($request->user_remove_id);
        if (!$user->account_accepted_at) {
            $user->delete();
        }
        return response()->json(true);
    }

    public function product_line()
    {
        $users = User::all();
        $product_lines = Range::all();
        return view('admin.product_line', ['users' => $users, 'product_lines' => $product_lines]);
    }

    public function create_product_line()
    {
        $users = User::all();
        return view('admin.create_product_line', ['users' => $users]);
    }

    public function store_product_line(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:8'],
            'property' => ['required', 'string', 'min:36'],
            'warranty_time' => ['required', 'integer', 'min:0']
        ]);

        Range::create(
            [
                'name' => $request->name,
                'property' => $request->property,
                'warranty_time' => $request->warranty_time,
            ]
        );
        return Redirect::back()->with(['message' => 'tạo dòng sản phẩm thành công']);
    }

    public function update_product_line(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:8'],
            'property' => ['required', 'string', 'min:36'],
            'warranty_time' => ['required', 'integer', 'min:0']
        ]);

        Range::find($request->product_line_id)->update(
            [
                'name' => $request->name,
                'property' => $request->property,
                'warranty_time' => $request->warranty_time,
            ]
        );

        return response()->json(true);
    }

    public function product_statistic()
    {
        $vendors = User::get_users_with_role('agent');
        $warranty_centers = User::get_users_with_role('warranty');
        $factories = User::get_users_with_role('factory');
        return view('admin.product_statistic', ['users' => User::all(), 'statuses' => Status::all(), 'vendors' => $vendors, 'warranty_centers' => $warranty_centers, 'factories' => $factories]);
    }

    public function print_product_statistic(Request $request)
    {
        $list_products = [];
        $data_inputs = $request->all();
        unset($data_inputs['_token']);
        if (count($data_inputs) == 0)
            $list_products[] = Product::excel_export(Product::all());
        else {
            foreach ($data_inputs as $key => $data_input) {
                foreach ($data_input as $input_value) {
                    if ($input_value != 0) {
                        $list_products[] = Product::excel_export(Product::where("{$key}_id", $input_value)->get(), 'created_at');
                    } else {
                        $list_products[] = Product::excel_export(Product::where("{$key}_id", '!=', null)->get(), 'created_at');
                    }
                }
            }
        }
        return (new ExcelsExport($list_products, ['Id', 'Status', 'Name', 'Property', 
        'Factory', 'Agent']))->download('product_admin.xlsx');
        
    }


    public function show_batches_recall() {

        $users = User::all();
        $batches_id = DB::table('products')->select('batch_id')
                        ->where('is_recall', 1) 
                        ->groupBy('batch_id')
                        ->get();
        $batches = [];
        foreach($batches_id as $id) {
            $batches[] = DB::table('batches')->where('id', $id->batch_id)->first();
        }

        return view('admin.show_batches_recall', ['users' => $users, 'batches' => $batches]);
    }

    public function new_batch_recall(Request $request) {
        $users = User::all();
        return view('admin.new_batch_recall', ['users' => $users]);
    }

    public function post_new_batch_recall(Request $request) {
        $request->validate(
            [
                'batch_id'=>'required|gt:0',
            ],
            [
                'batch_id.required'=>'Vui lòng nhập trường này',
                'batch_id.gt'=>'Vui lòng nhập đúng định dạng'
            ]
        );

        $check_batch_exist = DB::table('batches')->where('id', $request->input('batch_id'))->first();
        if(!$check_batch_exist) {
            return redirect()->back()->with(['message'=> 'Không tồn tại lô hàng này']);
        }

        $check_is_recall = (DB::table('products')->where('batch_id', $request->input('batch_id'))->first())->is_recall;
        
        if($check_is_recall) {
            return redirect()->back()->with(['message'=> 'Lô hàng đã được thu hồi']);
        }

        $message = Warranty::product_recall($request->input('batch_id'));

        return redirect()->back()->with(['message'=> $message]);
    }

    public function return_batch_recall($id) {
        $message = Warranty::return_product_recall($id);

        return redirect()->back()->with(['message'=> $message]);
    }

}
