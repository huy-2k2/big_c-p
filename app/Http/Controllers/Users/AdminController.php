<?php

namespace App\Http\Controllers\Users;

use App\Events\CreateNotifiEvent;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Range;
use App\Models\Status;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class AdminController extends Controller
{

    public function index()
    {
        return redirect()->route('admin.product_line');
    }

    public function create_notifi()
    {
        $users = User::all();
        $vendors = User::get_users_with_role('vendor');
        $warranty_centers = User::get_users_with_role('warranty_center');
        $factories = User::get_users_with_role('factory');
        return view('admin.create_notifi', ['users' => $users, 'vendors' => $vendors, 'warranty_centers' => $warranty_centers, 'factories' => $factories]);
    }

    public function store_notifi(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'min:8'],
            'content' => ['required', 'string', 'min:16'],
        ]);
        if (!$request->has('vendor') && !$request->has('factory') && !$request->has('warranty_center')) {
            return back()->withInput();
        }

        $notification = Notification::create([
            'title' => $request->title,
            'content' => $request->content
        ]);

        $datas_users = [
            ['role' => 'factory', 'ids' => $request->factory ?? []],
            ['role' => 'warranty_center', 'ids' => $request->warranty_center ?? []],
            ['role' => 'vendor', 'ids' => $request->vendor ?? []],
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
            'warranty_period_time' => ['required', 'integer', 'min:0']
        ]);

        Range::create(
            [
                'name' => $request->name,
                'property' => $request->property,
                'warranty_period_time' => $request->warranty_period_time,
            ]
        );
        return Redirect::back()->with(['message' => 'tạo dòng sản phẩm thành công']);
    }

    public function update_product_line(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:8'],
            'property' => ['required', 'string', 'min:36'],
            'warranty_period_time' => ['required', 'integer', 'min:0']
        ]);

        Range::find($request->product_line_id)->update(
            [
                'name' => $request->name,
                'property' => $request->property,
                'warranty_period_time' => $request->warranty_period_time,
            ]
        );

        return response()->json([1, 2, 3]);
    }

    public function product_statistic()
    {
        $vendors = User::get_users_with_role('vendor');
        $warranty_centers = User::get_users_with_role('warranty_center');
        $factories = User::get_users_with_role('factory');
        return view('admin.product_statistic', ['users' => User::all(), 'statuses' => Status::all(), 'vendors' => $vendors, 'warranty_centers' => $warranty_centers, 'factories' => $factories]);
    }

    public function print_product_statistic(Request $request)
    {
        return $request;
    }
}
