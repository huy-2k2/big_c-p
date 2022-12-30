@extends('layouts.admin')
@section('content')
    @php
        $tbody = []    
    @endphp
    @foreach ($users as $user)
        @if (!$user->account_accepted_at)
            @php
                $tbody[] = [
                        'data-id' => $user->id,
                        ['title' => $user->name, 'class' => 'open-user-detail-btn underline cursor-pointer'],
                        $user->email,
                        "$user->created_at",
                        ['title' => 'chấp thuận', 'class' => 'accept-user font-medium text-blue-600 cursor-pointer hover:underline'],
                        ['title' => 'xóa', 'class' => 'remove-user font-medium text-red-600 cursor-pointer hover:underline']
                ]
            @endphp
        @endif
    @endforeach
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg custom-scrollbar">
        @include('components.table', ['title' => 'người dùng', 'ths' => ['tên', 'email', 'thời gian tạo', ['title' => 'chấp nhận', 'sr_only' => true], ['title' => 'xóa', 'sr_only' => true]], 'tbody' => $tbody])
    </div>
    <script>
        ;(() => {
            const accept_user_btns = document.querySelectorAll('.accept-user')
            const remove_user_btns = document.querySelectorAll('.remove-user')
            const table_body = document.querySelector('tbody')
            accept_user_btns.forEach(btn => {
                btn.onclick = function() {
                    handle_remove_submited(this)
                    axios.post('{{ route('admin.accept_user.store')}}', {
                        access_token: getCookie('access_token'),
                        user_id: '{{ Auth::user()->id }}',
                        user_accept_id: this.getAttribute('data-id')
                    })
                    .then(({data}) => {
                        toastr.options = {
                            "closeButton" : true,
                            "progressBar" : true
                        }
                        toastr.success("chấp nhận người dùng thành công");
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
        })();
    </script>
    @parent
@endsection     