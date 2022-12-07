@extends('layouts.admin')
@section('content')
    <div class="p-5">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg custom-scrollbar">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <caption class="p-5 text-lg font-semibold text-left text-gray-900 bg-white dark:text-white dark:bg-gray-800">
                    Thông báo
                </caption>
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            STT
                        </th>
                        <th scope="col" class="px-6 py-3">
                            tiêu đề
                        </th>
                        <th scope="col" class="px-6 py-3">
                            nội dung
                        </th>
                        <th scope="col" class="px-6 py-3">
                           thời gian tạo
                        </th>
                        <th scope="col" class="px-6 py-3">
                            <span class="sr-only">chi tiết</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $stt = 1;    
                    @endphp
                    @foreach ($notifications as $notification)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $stt }}
                        </th>
                        <td class="px-6 min-w-[200px] py-4">
                            {{ $notification['title'] }}
                        </td>
                        <td class="px-6 min-w-[300px] py-4">
                            {{ $notification['content'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $notification['created_at'] }}
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap open-notifi-btn" data-index='{{ $notification->id }}'>
                            <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">chi tiết</a>
                        </td>
                    </tr>
                        @php
                            $stt++;    
                        @endphp
                    @endforeach
                </tbody>
            </table>
        </div>  
    </div>
    @foreach ($notifications as $notification)
        <div id="notification-{{ $notification->id }}" class="fixed inset-0 z-30 items-center justify-center hidden p-5 bg-black notifi bg-opacity-20">
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg max-h-[500px] custom-scrollbar overflow-y-auto">
                @include('components.button_close', ['id' => "close-notifi-{$notification->id}", 'data_index' => $notification->id, 'btn_close_class' => 'close-detail-notifi'])
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                STT
                            </th>
                            <th scope="col" class="px-6 py-3">
                                tên người dùng
                            </th>
                            <th scope="col" class="px-6 py-3">
                                thời gian đọc
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $stt_ = 0;    
                        @endphp
                        @foreach ($notification->users as $user)
                            @php
                                $stt_++;    
                            @endphp
                               <tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $stt_ }}
                                </th>
                                <td data-id='{{ $user->id }}' class="px-6 py-4 underline cursor-pointer open-user-detail-btn">
                                    {{ $user->name }}
                                </td>
                                <td class="px-6 py-4 readed_time_{{ $user->id }}">
                                    {{ $user->pivot->readed_at ?? 'chưa đọc' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
    @parent
    <script>
        ;(() => {
            const close_notifi_btns = document.querySelectorAll('.close-detail-notifi')
            const open_notifi_btns = document.querySelectorAll('.open-notifi-btn')
            close_notifi_btns.forEach(btn => {
                btn.onclick = function() {
                    const index = this.getAttribute('data-index')
                    document.querySelector(`#notification-${index}`).classList.remove('active')
                }
            });
            open_notifi_btns.forEach(btn => {
                btn.onclick = function() {
                    const index = this.getAttribute('data-index')
                    document.querySelector(`#notification-${index}`).classList.add('active')
                }
            });

            function handle_readed_notifi({data}) {

                document.querySelector(`#notification-${data.notifi_id} .readed_time_${data.user_id}`).innerText = data.time
            }

            window.addEventListener('load', function() { 
               
               Echo.private(`notification_readed`)
               .listen('ReadedNotifiEvent', handle_readed_notifi)
           })
        })();
    </script>
@endsection     