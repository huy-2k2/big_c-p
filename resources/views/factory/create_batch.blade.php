@extends('layouts.factory')
@section('content')
<form method="post" action='{{ route('factory.create_batch_post') }}'>
  <div class="form-group">
    <label for="range">Dòng sản phẩm</label>
    <input type="range" class="form-control" id="range" name="range">
  </div>
  <div class="form-group">
    <label for="quantity">Số lượng</label>
    <input type="quantity" class="form-control" id="quantity" name="quantity">
  </div>
  <div class="form-group">
    <label for="depot">Kho chứa</label>
    <input type="depot" class="form-control" id="depot" name="depot">
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
@endsection