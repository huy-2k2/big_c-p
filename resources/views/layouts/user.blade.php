@extends('layouts.app')
@section('app')
    <div class="flex items-start justify-start w-screen">
        <div id="sidebar" class="flex-shrink-0 bg-white translate-x-0 z-30 absolute top-0 left-0 lg:relative w-[280px] min-h-screen max-h-screen flex flex-col duration-300 overflow-x-hidden">
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
                        <div id="notifi-toggle" class="relative cursor-pointer">
                            <span class="text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                                </svg>
                            </span>
                            <span id="number-not-readed" class="absolute -top-1 -right-1 flex items-center justify-center w-4 h-4 text-white bg-red-500 rounded-full text-[12px]">
                                {{ Auth::user()->not_readed_notifications->count() }}
                            </span>
                        </div>
                        <div id="notifi-menu" class="hidden absolute right-0 w-[300px] bg-white z-10 border border-gray-300 rounded-lg shadow-lg top-full">
                            <div class="p-5 border border-transparent border-b-gray-200">
                                <div class="flex items-center justify-start mb-2 text-gray-800 gap-x-3">
                                    <span class="flex items-center justify-center w-6 h-6 p-0.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                                        </svg>
                                    </span>
                                    <span class="text-sm font-medium">Notifications</span>
                                </div>
                                <div class="flex items-center justify-start text-sm font-medium text-blue-500 gap-x-3">
                                    <span id="get-not-readed" class="cursor-pointer hover:text-blue-800">Ch??a xem</span>
                                    <span id="get-readed" class="cursor-pointer hover:text-blue-800">???? xem</span>
                                </div>
                            </div>
                            <div class="max-h-[350px] overflow-y-auto custom-scrollbar">
                                <ul id="notifi-menu-not-readed">
                                    @foreach (Auth::user()->not_readed_notifications as $notification)
                                            <li data-title="{{ $notification->title }}" data-content="{{ $notification->content }}" data-created_at="{{ $notification->created_at }}" data-index="{{ $notification->id }}" class="flex flex-col px-5 py-2 pr-1 border border-transparent cursor-pointer not-readed notification-summary gap-y-1 border-b-gray-200">
                                                <span class="text-sm font-medium line-clamp-1">{{ $notification->title }}</span>
                                                <span class="text-[12px] text-gray-500">
                                                    @include('lib.second_to_date', ['second' => time() -  strtotime($notification->created_at)])
                                                </span>
                                            </li>
                                    @endforeach
                                </ul>
                                <ul id="notifi-menu-readed" class="hidden">
                                    @foreach (Auth::user()->readed_notifications as $notification)
                                    <li data-title="{{ $notification->title }}" data-content="{{ $notification->content }}" data-created_at="{{ $notification->created_at }}" data-readed_at="{{ $notification->pivot->readed_at }}" data-index="{{ $notification->id }}" class="flex flex-col px-5 py-2 pr-1 border border-transparent cursor-pointer notification-summary gap-y-1 border-b-gray-200">
                                        <span class="text-sm font-medium line-clamp-1">{{ $notification->title }}</span>
                                                <span class="text-[12px] text-gray-500">
                                                    @include('lib.second_to_date', ['second' => time() -  strtotime($notification->created_at)])
                                                </span>
                                            </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        
                        <div id="user-toggle" class="flex items-center justify-center gap-2 cursor-pointer">
                            <img class="object-cover w-10 h-10 rounded-full shadow-sm" src="{{ url("images/".Auth::user()->role->name.'.jpg') }}" alt="">
                            <span class="text-sm font-semibold text-gray-600 capitalize">{{explode(' ', Auth::user()->name)[sizeof(explode(' ', Auth::user()->name)) - 1]}}</span>
                        </div>
                        <ul id="user-menu" class="absolute right-0 z-10 hidden text-gray-500 bg-white border border-gray-300 rounded-md shadow-sm top-full w-max">
                            <li id="open-account" class="px-3 py-1.5 cursor-pointer hover:text-gray-800 flex items-center justify-start gap-x-2">
                                <span class="flex items-center justify-center w-6 h-6 p-0.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                    </svg>
                                </span>
                                <span>t??i kho???n</span>
                            </li>
                            <li id="open-change-password" class="px-3 py-1.5 cursor-pointer hover:text-gray-800 flex items-center justify-start gap-x-2">
                                <span class="flex items-center justify-center w-6 h-6 p-0.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                                    </svg>                                  
                                </span>
                                <span>?????i m???t kh???u</span>
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
                                        <span>????ng xu???t</span>
                                    </button>
                                </form>
                            </li>
                        </ul>
                </div>
            </div>
            <div class="relative flex-grow overflow-y-auto custom-scrollbar">
                <div class="absolute w-full h-full max-w-full p-5">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    <div id="account-info" class="fixed top-0 left-0 z-30 hidden w-full min-h-screen p-5 bg-gray-900 bg-opacity-20">
        <div class="overflow-hidden relative w-[800px] max-h-[500px] custom-scrollbar overflow-y-auto max-w-full mx-auto mt-20 bg-white rounded-xl px-5 py-10">
            <div class="mb-8">
                @include('components.heading', ['text' => 'Th??ng tin t??i kho???n'])
            </div>
         
             @include('components.account_info', ['name' => Auth::user()->name, 'email' => Auth::user()->email, 'role' => Auth::user()->role->name, 'province' => Auth::user()->address->province, 'district' => Auth::user()->address->district, 'sub_district' => Auth::user()->address->sub_district, 'created_at' => Auth::user()->created_at, 'account_accepted_at' => Auth::user()->account_accepted_at])
             @include('components.button_close', ['id' => 'close-account'])
        </div>
    </div>
    <div id="change-password-form" class="@if(Session::has('password_changed') && !Session::get('password_changed')) active @endif fixed top-0 left-0 z-30 hidden w-full min-h-screen p-5 bg-gray-900 bg-opacity-20">
        <div class="overflow-hidden relative w-[800px] max-h-[500px] custom-scrollbar overflow-y-auto max-w-full mx-auto mt-20 bg-white rounded-xl px-5 py-10">
            <div class="mb-8">
                @include('components.heading', ['text' => '?????i m???t kh???u'])
            </div>
             <form method="POST" action="{{ route('password.change') }}" class="flex flex-col gap-y-5">
                @csrf
                @include('components.input', ['type' => 'password','label' => 'm???t kh???u c??', 'name' => 'password_old'])
                @include('components.input', ['type' => 'password','label' => 'm???t kh???u m???i', 'name' => 'password'])
                @include('components.input', ['type' => 'password','label' => 'x??c nh???n m???t kh???u m???i', 'name' => 'password_confirmation'])
                @include('components.button_submit', ['text' => '?????i m???t kh???u'])
            </form>
             @include('components.button_close', ['id' => 'close-change-password'])
        </div>
    </div>
   
    <div class="fixed top-0 z-30 hidden w-full min-h-full p-5 bg-gray-900 notification-detail bg-opacity-20" >
        <div class="p-5 overflow-y-auto max-h-[350px] custom-scrollbar overflow-x-hidden bg-white rounded-lg w-[500px] max-w-full  relative mx-auto mt-20">
            <div class="mb-8">
                @include('components.heading', ['text' => 'Chi ti???t th??ng b??o'])
            </div>
            <div class="flex flex-col mb-5 gap-y-1">
                <span class="text-lg text-gray-600">Ti??u ?????:</span>
                <h3 class="ml-4 text-sm text-gray-500 notification-title"></h3>
            </div>
            <div class="flex flex-col mb-8 gap-y-1">
                <span class="text-lg text-gray-600">N???i dung:</span>
                <h3 class="ml-4 text-sm text-gray-500 notification-content"> </h3>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="notification-created_at">
                    @include('components.input_float_disable', ['value' => '', 'label' => 'th???i gian t???o'])
                </div>
                <div class="notification-readed_at">
                    @include('components.input_float_disable', ['value' => '', 'label' => 'th???i gian ?????c'])
                </div>
                <div class="notification-reading">
                    @include('components.input_float_disable', ['value' => '??ang ?????c', 'label' => 'tr???ng th??i'])
                </div>
            </div>
            @include('components.button_close', ['id' => 'btn-close-notification'])
        </div>
    </div>
    @include('lib.auto_toggle', ['toggle_btn' => '#notifi-toggle', 'main' => '#notifi-menu', 'dependents_element' => ['.notification-detail']])
    @include('lib.auto_toggle', ['toggle_btn' => '#user-toggle', 'main' => '#user-menu', 'dependents_element' => ['#account-info', '#change-password-form']])
    <script>
        (() => {
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
            const btn_get_readed = document.querySelector('#get-readed')
            const btn_get_not_readed = document.querySelector('#get-not-readed')
            
            const notifi_menu = document.querySelector('#notifi-menu')
            const notifi_toggle_btn = document.querySelector('#notifi-toggle')

            const notifications_summary = document.querySelectorAll('.notification-summary')
            const close_notification_btn = document.querySelector('#btn-close-notification')

            const notifi_menu_readed =  document.querySelector('#notifi-menu-readed')
            const notifi_menu_not_readed =  document.querySelector('#notifi-menu-not-readed')


            close_notification_btn.onclick = function() {   
                const notification_detail = document.querySelector(`.notification-detail`)
                notification_detail.classList.remove('active')
            }

            function handle_change_size() {
                handle()
                function handle() {
                    if(document.body.clientWidth < 1024) {
                        sidebar.classList.add('not-active')
                    } else {
                        sidebar.classList.remove('not-active')
                    }
                }
                window.addEventListener('resize', handle)
            }

            handle_change_size()

            function handle_notifi_open() {
                const notification_detail = document.querySelector(`.notification-detail`)
                    notification_detail.classList.add('active')
                    notification_detail.querySelector('.notification-title').innerText = this.getAttribute('data-title')
                    notification_detail.querySelector('.notification-content').innerText = this.getAttribute('data-content')
                    notification_detail.querySelector('.notification-created_at input').value = this.getAttribute('data-created_at')
                    notification_detail.querySelector('.notification-readed_at input').value = this.getAttribute('data-readed_at')
                    const index = this.getAttribute('data-index')
                    if(this.classList.contains('not-readed')) {
                        notification_detail.querySelector('.notification-readed_at').classList.add('hidden')
                        notification_detail.querySelector('.notification-reading').classList.remove('hidden')
                        ;(async () => {
                            const response = await post_data('notification/mark_readed', {notification_id: index, user_id: '{{ Auth::user()->id }}', access_token: getCookie('access_token')})
                            document.querySelector('#number-not-readed').innerText -= 1;
                            this.classList.remove('not-readed')
                            notifi_menu_not_readed.removeChild(this)
                            notifi_menu_readed.insertBefore(this, notifi_menu_readed.children[0])
                            this.setAttribute('data-readed_at', response)
                            console.log(response);
                        })();
                    } else {
                        notification_detail.querySelector('.notification-readed_at').classList.remove('hidden')
                        notification_detail.querySelector('.notification-reading').classList.add('hidden')
                    }
            }

            notifications_summary.forEach(notififaction_summary => {
                notififaction_summary.onclick = handle_notifi_open
            });


            btn_get_not_readed.onclick = () => notifi_menu.classList.remove('readed')
            btn_get_readed.onclick = () => notifi_menu.classList.add('readed') 

            open_account_btn.onclick = () => account_info_form.classList.add('active')
            close_account_btn.onclick = () => account_info_form.classList.remove('active')

            open_changepassword_btn.onclick = () => change_password_form.classList.add('active')
            close_changepassword_btn.onclick = () => change_password_form.classList.remove('active')

           
            sidebar_hidden_btn.onclick = () => sidebar.classList.add('not-active')
            
            sidebar_toggle_btn.onclick = () => sidebar.classList.toggle('not-active')
            

            function handle_notifi_created({data}) {
                const {notification, time} = data
                const number_notifi = document.querySelector('#number-not-readed');
                number_notifi.innerText = parseInt(number_notifi.innerText) + 1
                const list_notifi = document.querySelector('#notifi-menu-not-readed');
                const li = document.createElement('li')
                li.setAttribute('class', 'flex flex-col px-5 py-2 pr-1 border border-transparent cursor-pointer not-readed notification-summary gap-y-1 border-b-gray-200')
                li.setAttribute('data-title', notification.title)
                li.setAttribute('data-content', notification.content)
                li.setAttribute('data-created_at', time)
                li.setAttribute('data-index', notification.id)
                li.innerHTML = `
                <span class="text-sm font-medium line-clamp-1"> ${notification.title}</span>
                 <span class="text-[12px] text-gray-500">
                    1 ph??t tr?????c
                 </span>
                `
                li.onclick = handle_notifi_open
                list_notifi.insertBefore(li, list_notifi.children[0])
            }
            
            window.addEventListener('load', function() { 
               
                Echo.private(`notifications.{{ Auth::user()->id }}`)
                .listen('CreateNotifiEvent', handle_notifi_created)
            })
            })();
            
            function handle_remove_submited(element)  {
                const table_body = document.querySelector('tbody')
                const id = element.getAttribute('data-id')
                table_body.removeChild(element.parentElement)
                const stt_users = document.querySelectorAll('.stt');
                console.log(stt_users);
                stt_users.forEach(stt => {
                    if(stt.getAttribute('data-id') > id)
                        stt.innerText -= 1
                })
            }
    </script>
   
@endsection