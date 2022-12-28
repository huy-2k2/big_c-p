@extends('layouts.agent')
@section('content')
<form method="GET" action='{{ route('agent.check_sell_to_customer') }}'>
  @csrf
  <div class="form-group">
    <label for="customer_id">Id người dùng</label>
    <input type="number" class="form-control" id="customer_id" name="customer_id" value="{{ old('customer_id') }}">
    @error('customer_id')
      <span style="color:red">{{ $message }}</span>
    @enderror
  </div>
  <div class="form-group">
    <label for="product_id">Id sản phẩm</label>
    <input type="number" class="form-control" id="product_id" name="product_id">
    @error('product_id')
      <span style="color:red">{{ $message }}</span>
    @enderror
  </div>
  <button type="submit" class="btn btn-primary">Kiểm tra</button>
</form>
@endsection