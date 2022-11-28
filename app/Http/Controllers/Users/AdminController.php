<?php

namespace App\Http\Controllers\Users;

use App\Events\CreateNotifiEvent;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['author:admin']);
    }

    public function index()
    {
        $users = User::all();
        return view('admin.main', ['users' => $users]);
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
                        broadcast(new CreateNotifiEvent($notification, $user->id));
                    }
                } else {
                    $notification->users()->attach($id);
                    broadcast(new CreateNotifiEvent($notification, $id));
                }
            }
        }

        return Redirect::back()->with(['message' => 'tạo thông báo thành công']);
    }
}
