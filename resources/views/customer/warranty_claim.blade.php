@extends('layouts.customer')
@section('content')
<form class="form-control" method="POST" action='{{ route('customer.send_warranty_claim') }}'>
  @csrf
  <ol>
    <input style="display:none"  name="product_id" value="{{ $product->id }}">
    <li>Id: sản phẩm: {{ $product->id }}</li>
    <li>Dòng sản phẩm: {{ (DB::table('ranges')->where('id', $product->range_id)->first())->name }}</li>
    <li>Nhà máy sản xuất: {{ (DB::table('users')->where('id', $product->factory_id)->first())->name }}</li>
    <li>Mua từ đại lý: {{ (DB::table('users')->where('id', $product->agent_id)->first())->name }}</li>
    <li>Số lần bảo hành: {{ $product->warranty_count }}</li>
    <li>Thời gian mua hàng: {{ $product->customer_buy_time }}</li>
    <li>Trạng thái: Đang ở {{ (DB::table('statuses')->where('id', $product->status_id)->first())->name }}</li>
    <li><label for="error_id">Lý do bảo hành: </label></li>
    <li>
      <select class="form-control" name="error_id" id="error_id">
        @foreach($product_errors as $product_error) 
          <option value={{ $product_error -> id }}>{{ $product_error -> name }}</option>
        
        @endforeach
    </select>
    </li>
    <li><label for="claim_reason">Chi tiết: </label></li>
    <li><input style="width:100%;height:50px" class="border border-info" type="text" name="claim_reason" id="claim_reason" placeholder="Điền lý do yêu cầu">
      @error('claim_reason')
    <span style="color:red">{{ $message }}</span>
  @enderror
    </li>
    <li>
      <button type="submit" class="mt-10 btn btn-danger">
        Yêu cầu bảo hành
      </button>
    </li>
  </ol>
</form>

@endsection