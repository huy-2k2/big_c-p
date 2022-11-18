@extends('layouts.auth')

@section('content')
<div class="w-[500px] max-w-full p-5">
    <form action="{{ route('password.update') }}" method="POST" class="flex flex-col w-full p-5 rounded-lg shadow-xl gap-y-4">
        @csrf
        <h2 class="text-2xl font-bold text-blue-600">
            Reset mật khẩu
        </h2>
        <input type="hidden" name="token" value="{{ $token }}">
        @include('components.input_disable', ['value' => $email ?? old('email'), 'name' => 'email']) 
        @include('components.input', ['name' => 'password', 'type' => 'password', 'label' => 'nhập mật khẩu mới'])        
        @include('components.input', ['name' => 'password_confirmation', 'type' => 'password', 'label' => 'xác nhận mật khẩu mới'])       
        @include('components.button_submit', ['text' => 'Reset'])
    </form>
</div>
@endsection
