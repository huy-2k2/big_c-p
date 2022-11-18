@extends('layouts.app')

@section('app')
<div class="flex items-center justify-center mt-20">
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
    {{-- <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Verify Your Email Address') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif

                    {{ __('Before proceeding, please check your email for a verification link.') }}
                    {{ __('If you did not receive the email') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="p-0 m-0 align-baseline btn btn-link">{{ __('click here to request another') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div> --}}
</div>
@endsection
