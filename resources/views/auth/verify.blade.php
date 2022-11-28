@extends('layouts.app')

@section('app')
<div class="flex items-center justify-center p-5 mt-20">
    <div class="w-[500px] max-w-full p-5 rounded-lg shadow-xl bg-white flex flex-col gap-y-4">
        @if(session('resent'))
            @include('components.text_notice', ['type_ntc' => 'success', 'text' => 'đã gửi lại email xác thực'])
        @else
            @include('components.text_notice', ['type_ntc' => 'success', 'text' => 'đã gửi email xác thực, một email vùa được gửi đến tài khoản gmail của bạn'])
        @endif

        @include('components.text_notice', ['type_ntc' => 'warning', 'text' => 'vui lòng kiểm tra email của bạn để xác nhận tài khoản, nếu chưa nhận được email? click vào nút phía dưới để gửi lại'])
        <form method="POST" action="{{ route('verification.resend') }}">
            @csrf
            @include('components.button_submit', ['text' => 'gửi lại email'])
        </form>
    </div>
</div>
@endsection
