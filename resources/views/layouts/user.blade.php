@extends('layouts.app')
@section('app')
    <div class="flex items-start justify-start">
        <div id="sidebar" class="flex-shrink-0 bg-white translate-x-0 z-10 absolute top-0 left-0 lg:relative w-[280px] min-h-screen max-h-screen flex flex-col duration-300 overflow-x-hidden">
                <div class="flex items-center justify-center gap-x-10 border border-gray-200 border-t-transparent h-[64px]">
                    <a href="{{ route('home') }}" class="flex items-center justify-center h-full">
                        <img src="{{ url('images/logo.jpg') }}" class="object-cover w-16 max-h-full" alt="logo">
                        <h2 class="text-xl font-bold uppercase sidebar-hidden text-primary whitespace-nowrap">Big corp</h2>
                    </a>
                    <span id="sidebar-hidden" class="flex lg:hidden cursor-pointer hover:text-gray-900 items-center justify-center w-6 h-6 p-[2px] text-gray-600 border border-current rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                    </span>
                </div>
            <div class="flex-grow px-5 overflow-y-auto border border-gray-200 custom-scrollbar py-7 border-t-transparent">
               @yield('menu')
            </div>
        </div>
        <div class="flex flex-col flex-grow h-screen">
            <div id="header" class="relative flex items-center justify-between flex-shrink-0 h-16 px-6 border border-gray-200 border-l-transparent border-t-transparent">
                <div id="sidebar-toggle" class="text-gray-700 cursor-pointer w-7">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </div>
                <div class="flex items-center justify-center gap-6">
                    @include('components.button_toggle', ['label' => 'dark/light' ])
                    <div id="user-toggle" class="flex items-center justify-center gap-2 cursor-pointer">
                        <img class="object-cover w-10 h-10 rounded-full shadow-sm" src="{{ url("images/".Auth::user()->role->name.'.jpg') }}" alt="">
                        <span class="text-sm font-semibold text-gray-600 capitalize">{{explode(' ', Auth::user()->name)[sizeof(explode(' ', Auth::user()->name)) - 1]}}</span>
                    </div>
                    <ul id="user-menu" class="absolute right-0 hidden text-gray-500 bg-white border border-gray-300 rounded-md shadow-sm top-full w-max">
                        <li id="open-account" class="px-3 py-1.5 cursor-pointer hover:text-gray-800 flex items-center justify-start gap-x-2">
                            <span class="flex items-center justify-center w-6 h-6 p-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>
                            </span>
                            <span>tài khoản</span>
                        </li>
                        <li id="open-change-password" class="px-3 py-1.5 cursor-pointer hover:text-gray-800 flex items-center justify-start gap-x-2">
                            <span class="flex items-center justify-center w-6 h-6 p-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                                </svg>                                  
                            </span>
                            <span>đổi mật khẩu</span>
                        </li>
                        <li class="px-3 py-2 border border-transparent cursor-pointer border-t-gray-300 hover:text-gray-800">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="flex w-full h-ful gap-x-2">
                                    <span class="flex items-center justify-center w-6 h-6 p-0.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                                        </svg>                                                                       
                                    </span>
                                    <span>đăng xuất</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="flex-grow overflow-y-auto custom-scrollbar">
                @yield('content')
            </div>
        </div>
    </div>
    <div id="account-info" class="fixed top-0 left-0 z-30 hidden w-full min-h-screen p-5 bg-gray-900 bg-opacity-20">
        <div class="overflow-hidden relative w-[800px] max-h-[500px] custom-scrollbar overflow-y-auto max-w-full mx-auto mt-20 bg-white rounded-xl px-5 py-10">
            <h1 class="mb-8 text-2xl text-gray-500">Thông tin tài khoản</h1>
             <div class="grid w-full gap-5 grid-col-1 lg:grid-cols-2">
                @include('components.input_float_disable', ['value' => Auth::user()->name, 'label' => 'họ và tên'])
                @include('components.input_float_disable', ['value' => Auth::user()->email, 'label' => 'email'])
                @include('components.input_float_disable', ['value' => Auth::user()->role->name, 'label' => 'vai trò'])
                @include('components.input_float_disable', ['value' => Auth::user()->address->province, 'label' => 'tỉnh / thành phố'])
                @include('components.input_float_disable', ['value' => Auth::user()->address->district, 'label' => 'quận / huyện'])
                @include('components.input_float_disable', ['value' => Auth::user()->address->sub_district, 'label' => 'xã / phường'])
                @include('components.input_float_disable', ['value' => Auth::user()->created_at, 'label' => 'thời gian tạo tài khoản'])
                @include('components.input_float_disable', ['value' => Auth::user()->account_accepted_at, 'label' => 'thời gian được chấp thuận'])
             </div>
             <span id="close-account" class="absolute top-0 right-0 z-50 flex items-center justify-center h-10 p-4 text-white bg-red-600 cursor-pointer w-14">
                 <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                 </svg>  
             </span>
        </div>
    </div>
    <div id="change-password-form" class="@if(Session::has('password_changed') && !Session::get('password_changed')) active @endif fixed top-0 left-0 z-30 hidden w-full min-h-screen p-5 bg-gray-900 bg-opacity-20">
        <div class="overflow-hidden relative w-[400px] max-w-full mx-auto mt-20 bg-white rounded-xl px-5 py-10">
            <h1 class="mb-8 text-2xl text-gray-500">Đổi mật khẩu</h1>
             <form method="POST" action="{{ route('password.change') }}" class="flex flex-col gap-y-5">
                @csrf
                @include('components.input', ['type' => 'password','label' => 'mật khẩu cũ', 'name' => 'password_old'])
                @include('components.input', ['type' => 'password','label' => 'mật khẩu mới', 'name' => 'password'])
                @include('components.input', ['type' => 'password','label' => 'xác nhận mật khẩu mới', 'name' => 'password_confirmation'])
                @include('components.button_submit', ['text' => 'đổi mật khẩu'])
            </form>
             <span id="close-change-password" class="absolute top-0 right-0 z-50 flex items-center justify-center h-10 p-4 text-white bg-red-600 cursor-pointer w-14">
                 <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                 </svg>  
             </span>
        </div>
    </div>
  
    <script>
        const sidebar = document.querySelector('#sidebar');
        const sidebar_toggle_btn = document.querySelector('#sidebar-toggle')
        const sidebar_hidden_btn = document.querySelector('#sidebar-hidden');
        const user_toggle_btn = document.querySelector('#user-toggle')
        const open_account_btn = document.querySelector('#open-account')
        const close_account_btn = document.querySelector('#close-account')
        const account_info_form = document.querySelector('#account-info')
        const open_changepassword_btn = document.querySelector('#open-change-password')
        const close_changepassword_btn = document.querySelector('#close-change-password')
        const change_password_form = document.querySelector('#change-password-form')

        open_account_btn.onclick = () => account_info_form.classList.add('active')
        close_account_btn.onclick = () => account_info_form.classList.remove('active')

        open_changepassword_btn.onclick = () => change_password_form.classList.add('active')
        close_changepassword_btn.onclick = () => change_password_form.classList.remove('active')

        user_toggle_btn.onclick = function() {
            this.classList.toggle('active')
        }

        sidebar_hidden_btn.onclick = () => sidebar.classList.add('not-active')
        

        sidebar_toggle_btn.onclick = () => sidebar.classList.toggle('not-active')
    </script>
@endsection