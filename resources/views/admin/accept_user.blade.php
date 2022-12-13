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
                                tên
                            </th>
                            <th scope="col" class="px-6 py-3">
                                email
                            </th>
                            <th scope="col" class="px-6 py-3 whitespace-nowrap">
                               thời gian tạo
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="sr-only">chi tiết</span>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="sr-only">chấp thuận</span>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="sr-only">xóa</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="tbody">
                        @php
                            $stt = 0;    
                        @endphp
                        @foreach ($users as $user)
                            @if (!$user->account_accepted_at)
                                @php
                                    $stt++;    
                                @endphp
                                <tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700">
                                    <th data-id="{{ $user->id }}" scope="row" class="px-6 py-4 font-medium text-gray-900 stt-user whitespace-nowrap dark:text-white">
                                        {{ $stt }}
                                    </th>
                                    <td data-id='{{ $user->id }}' class="px-6 py-4 underline cursor-pointer open-user-detail-btn whitespace-nowrap">
                                        {{ $user->name }}
                                    </td>
                                    <td data-id='{{ $user->id }}' class="px-6 py-4 whitespace-normal">
                                        {{ $user->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $user->created_at}}
                                    </td>
                                    <td data-id="{{ $user->id }}" class="px-6 py-4 text-right whitespace-nowrap accept-user">
                                        <div  class="font-medium text-blue-600 cursor-pointer hover:underline">
                                            chấp nhận
                                        </div>
                                    </td>
                                    <td data-id="{{ $user->id }}" class="px-6 py-4 text-right whitespace-nowrap remove-user">
                                        <div class="font-medium text-red-600 cursor-pointer hover:underline">
                                            xóa
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <script>
            ;(() => {
                const accept_user_btns = document.querySelectorAll('.accept-user')
                const remove_user_btns = document.querySelectorAll('.remove-user')
                const table_body = document.querySelector('.tbody')
                accept_user_btns.forEach(btn => {
                    btn.onclick = function() {
                        handle_remove_submited(this)
                        axios.post('{{ route('admin.accept_user.store')}}', {
                            access_token: getCookie('access_token'),
                            user_id: '{{ Auth::user()->id }}',
                            user_accept_id: this.getAttribute('data-id')
                        })
                        .then(({data}) => {
                            if(data) {
                                toastr.options = {
                                    "closeButton" : true,
                                    "progressBar" : true
                                }
                                toastr.success("chấp nhận người dùng thành công");
                            }
                        })
                    }
                });

                remove_user_btns.forEach(btn => {
                    btn.onclick = function() {
                        handle_remove_submited(this)
                        axios.post('{{ route('admin.accept_user.remove')}}', {
                            access_token: getCookie('access_token'),
                            user_id: '{{ Auth::user()->id }}',
                            user_remove_id: this.getAttribute('data-id')
                        })
                        .then(({data}) => {
                            if(data) {
                                toastr.options = {
                                    "closeButton" : true,
                                    "progressBar" : true
                                }
                                toastr.success("xóa người dùng thành công");
                            }
                        })
                    }
                });

                

                function handle_remove_submited(element)  {
                    const id = element.getAttribute('data-id')
                    table_body.removeChild(element.parentElement)
                    const stt_users = document.querySelectorAll('.stt-user');
                    stt_users.forEach(stt => {
                        if(stt.getAttribute('data-id') > id)
                            stt.innerText -= 1
                    })
                }
            })();
        </script>
    @parent
@endsection     