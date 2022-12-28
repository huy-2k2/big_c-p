@extends('layouts.admin')
@section('content')
  <table class="table">
    <caption>List of users</caption>
    <thead>
      <tr>
        <th scope="col">Id tài khoản</th>
        <th scope="col">Tên</th>
        <th scope="col">Email</th>
        <th scope="col">Xác thực lúc</th>
        <th scope="col">Chấp nhận lúc</th>
        <th scope="col">Vai trò</th>
        <th scope="col">Địa chỉ</th>
      </tr>
    </thead>
    <tbody>
      @foreach($accounts as $account) 
        <tr>
          <td>{{ $account -> id }}</td>
          <td>{{ $account -> name }}</td>
          <td>{{ $account -> email }}</td>
          <td>{{ $account -> email_verified_at }}</td>
          <td>{{ $account -> account_accepted_at }}</td>
          <td>{{ $roles[$account->id] }}</td>
          <td>{{ $addresses[$account->id] }}</td>
        </tr>
      
      @endforeach
    </tbody>
  </table>
@endsection     