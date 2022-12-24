@extends('layouts.admin')
@section('content')
    @php
        $tbody = []
    @endphp
    @foreach ($notifications as $notification)
        @php
            $tbody[] = [
                'data-id' => $notification->id,
                $notification->title,
                $notification->content,
                "$notification->created_at",
                ['title' => '<a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">chi tiết</a>', 'class' => 'open-notifi-btn']

            ]    
        @endphp
    @endforeach
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg custom-scrollbar">
            @include('components.table', ['title' => 'thông báo', 'ths' => ['tiêu đề', 'nội dung', 'thời gian tạo', ['title' => 'chi tiết', 'sr_only' => true]], 'tbody' => $tbody])
        </div>  
    @foreach ($notifications as $notification)
        <div id="notification-{{ $notification->id }}" class="fixed inset-0 z-30 items-center justify-center hidden p-5 bg-black notifi bg-opacity-20">
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg max-h-[500px] custom-scrollbar overflow-y-auto">
                @include('components.button_close', ['id' => "close-notifi-{$notification->id}", 'data_index' => $notification->id, 'btn_close_class' => 'close-detail-notifi'])
                @php
                    $_tbody = []     
                @endphp
                @foreach ($notification->users as $user)
                    @php
                        $_tbody[] = [
                            'data-id' => $user->id,
                            ['title' => $user->name, 'class' => 'open-user-detail-btn underline cursor-pointer'],
                            ['title' => $user->pivot->readed_at ?? 'chưa đọc', 'class' => "readed_time_$user->id"]
                        ]    
                    @endphp
                @endforeach
                @include('components.table', ['title' => '', 'ths' => ['tên người dùng', 'thời gian đọc'] , 'tbody' => $_tbody])
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
                    const index = this.getAttribute('data-id')
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