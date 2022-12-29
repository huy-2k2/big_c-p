@extends('layouts.admin')
@section('content')
@php
    $tbody = []
@endphp
@foreach ($users as $user)
    @php
        $tbody[] = [
          $user->id,
          $user->name,
          $user->email,
          $user->address->province . ', ' . $user->address->district . ', '. $user->address->sub_district,
          "$user->created_at",
          "$user->account_accepted_at"
        ]
    @endphp
@endforeach
<div class="relative overflow-x-auto shadow-md sm:rounded-lg custom-scrollbar">
  @include('components.table', ['title' => 'tài khoản người dùng', 'ths' => ['id',  'tên', 'email','địa chỉ', 'thời gian tạo' , 'thời gian chấp nhận'], 'tbody' => $tbody])
</div>
@endsection     