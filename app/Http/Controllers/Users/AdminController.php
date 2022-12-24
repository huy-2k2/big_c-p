<?php

namespace App\Http\Controllers\Users;

use App\Events\CreateNotifiEvent;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{

    public function index()
    {
        $users = User::all();
        return view('admin.main', ['users' => $users]);
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
            
            if($user->role_id == 2 /*factory*/) {
                DB::table('factories')->insert(['user_id'=>$user->id]);
            } else if($user->role_id == 3 /*warranty*/) {
                DB::table('warranties')->insert(['user_id'=>$user->id]);
            } else if($user->role_id == 4 /*agent*/) {
                DB::table('agents')->insert(['user_id'=>$user->id]);
            } else if($user->role_id == 5 /*customer*/) {
                DB::table('customers')->insert(['user_id'=>$user->id]);
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

    public function product_ranges() {
        $ranges = DB::table('ranges')->get();
    
        return view('admin.product_ranges', compact('ranges'));
    }

    public function add_product_range() {
        return view('admin.add_product_range');
    }

    public function post_add_product_range(Request $request) {
        $request->validate(
            [
                'name'=>'required',
                'property'=>'required',
                'warranty_period_time'=>'required',
            ],
            [
                'property.required'=>'Vui lòng nhập trường này',
                'name.required'=>'Vui lòng nhập trường này',
                'warranty_period_time.required'=>'Vui lòng nhập trường này',
            ]
        );

        DB::table('ranges')->insert([
            'name' => $request->input('name'),
            'property' => $request->input('property'),
            'warranty_period_time' => $request->input('warranty_period_time'),
        ]);

        return redirect()->route('admin.product_ranges');
    }

    public function delete_product_range($id) {
        $range_delete = DB::table('ranges')->where('id', '=', $id)->get();
        if($range_delete->first()) {
            DB::table('ranges')->where('id', '=', $id)->delete();
        } else {
            return redirect()->route('admin.product_ranges');
        }
        return redirect()->route('admin.product_ranges');
    }

    public function edit_product_range($id) {
        $range_edit = DB::table('ranges')->where('id', '=', $id)->get()->first();
        if($range_edit) {
            return view('admin.edit_product_range', compact('range_edit'));
        } else {
            return redirect()->route('admin.product_ranges');
        }
    }

    public function put_edit_product_range($id, Request $request) {
        $request->validate(
            [
                'name'=>'required',
                'property'=>'required',
                'warranty_period_time'=>'required',
            ],
            [
                'property.required'=>'Vui lòng nhập trường này',
                'name.required'=>'Vui lòng nhập trường này',
                'warranty_period_time.required'=>'Vui lòng nhập trường này',
            ]
        );

        DB::table('ranges')->where('id', $id)->update([
            'name' => $request->input('name'),
            'property' => $request->input('property'),
            'warranty_period_time' => $request->input('warranty_period_time'),
        ]);

        return redirect()->route('admin.product_ranges');
    }
}
