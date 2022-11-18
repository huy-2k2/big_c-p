@extends('layouts.auth')

@section('content')
<div class="w-[500px] max-w-full p-5">
    <form action="{{ route('password.email') }}" method="POST" class="flex flex-col w-full p-5 rounded-lg shadow-xl gap-y-4">
        @csrf
        <h2 class="text-2xl font-bold text-blue-600">
            Quên mật khẩu
        </h2>
        @if (session('status'))
            @include('components.text_notice', ['text' => session("status"), 'type_ntc' => 'success'])
        @endif
        @include('components.input', ['name' => 'email', 'label' => 'nhập email tài khoản cần tìm'])
        @include('components.button_submit', ['text' => 'lấy lại mật khẩu'])
    </form>
</div>
@endsection
