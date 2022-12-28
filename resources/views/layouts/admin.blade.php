@php
    $items = [
        ['link' => route('admin.product_statistic'), 'text' => 'thống kê', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" /></svg>'],
        ['link' => '', 'text' => 'Người dùng', 'children' => [['link' => route('admin.accept_user'), 'text' => 'cấp tài khoản mới'], ['link' => route('admin.show_account'), 'text' => 'quản lý tài khoản']], 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>'],
        ['link' => '', 'text' => 'Dòng sản phẩm', 'children' => [['link' => route('admin.create_product_line'), 'text' => 'tạo mới'], ['link' => route('admin.product_line'), 'text' => 'xem']], 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>'],
        ['link' => '', 'text' => 'Lô hàng thu hồi', 'children' => [['link' => route('admin.show_batches_recall'), 'text' => 'Xem các lô hiện tại'], ['link' => route('admin.new_batch_recall'), 'text' => 'Thu hồi lô hàng mới']], 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>'],
        ['link' => '', 'children' => [['link' => route('admin.create_notifi'), 'text' => 'tạo mới'], ['link' => route('admin.notifi'), 'text' => 'xem']], 'text' => 'thông báo', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0M3.124 7.5A8.969 8.969 0 015.292 3m13.416 0a8.969 8.969 0 012.168 4.5" /></svg>'],
        ['link' => route('admin.show_product'), 'text' => 'Quản lý sản phẩm', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" /></svg>'],
    
    ];        
@endphp 

@extends('layouts.user')
@section('menu')
    @include('components.sidebar_menu',['items' => $items])
@endsection

@section('content')
    @foreach ($users as $user)
        <div id="account-info-{{ $user->id }}" class="fixed top-0 left-0 z-30 hidden w-full min-h-screen p-5 bg-gray-900 account-detail bg-opacity-20">
            <div class="overflow-hidden relative w-[800px] max-h-[500px] custom-scrollbar overflow-y-auto max-w-full mx-auto mt-20 bg-white rounded-xl px-5 py-10">
                <div class="mb-8">
                    @include('components.heading', ['text' => 'Thông tin tài khoản'])
                </div>
                @include('components.account_info', ['name' => $user->name, 'email' => $user->email, 'role' => $user->role->name, 'province' => $user->address->province, 'district' => $user->address->district, 'sub_district' => $user->address->sub_district, 'created_at' => $user->created_at, 'account_accepted_at' => $user->account_accepted_at ?? 'chưa chấp thuận'])
                @include('components.button_close', ['id' => "close-account-{$user->id}", 'data_index' => $user->id, 'btn_close_class' => 'close-detail-account'])
            </div>
        </div>
    @endforeach
    <script>
        (() => {
            const open_user_detail_btns = document.querySelectorAll('.open-user-detail-btn')
            const close_detail_account_btns = document.querySelectorAll('.close-detail-account')
            open_user_detail_btns.forEach(open_btn => {
                open_btn.onclick = function() {
                    document.querySelector(`#account-info-${this.getAttribute('data-id')}`).classList.add('active')
                }
            });
            close_detail_account_btns.forEach(close_btn => {
                close_btn.onclick = function() {
                    const id = this.getAttribute('data-index')
                    console.log(id);
                    document.querySelector(`#account-info-${id}`).classList.remove('active')
                }
            })
        })()
    </script>
@endsection