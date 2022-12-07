@php
    $items = [
        ['link' => '#', 'text' => 'Dashboard', 'children' => [['link' => '#', 'text' => 'children 1'], ['link' => '#', 'text' => 'children 2'], ['link' => '#', 'text' => 'children 3']], 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>'],
        ['link' => '#', 'text' => 'email', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>'],
        ['link' => '#', 'text' => 'calendar', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>'],
        ['link' => '#', 'text' => 'chat', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" /></svg>'],
        ['link' => '', 'children' => [['link' => route('admin.create_notifi'), 'text' => 'tạo thông báo'], ['link' => route('admin.notifi'), 'text' => 'xem thông báo']], 'text' => 'thông báo', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0M3.124 7.5A8.969 8.969 0 015.292 3m13.416 0a8.969 8.969 0 012.168 4.5" /></svg>'],
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
                @include('components.account_info', ['name' => $user->name, 'email' => $user->email, 'role' => $user->role->name, 'province' => $user->address->province, 'district' => $user->address->district, 'sub_district' => $user->address->sub_district, 'created_at' => $user->created_at, 'account_accepted_at' => $user->account_accepted_at])
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