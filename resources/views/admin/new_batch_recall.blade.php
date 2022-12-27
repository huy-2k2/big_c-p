@extends('layouts.admin')
@section('content')
<form method="GET" action='{{ route('admin.post_new_batch_recall') }}'>
  <div class="form-group">
    <label for="batch_id">Mã lô hàng</label>
    <input type="number" class="form-control" name="batch_id" id="batch_id" placeholder="Nhập mã lô hàng">
  </div>
  @error('batch_id')
    <span style="color:red">{{ $message }}</span>
  @enderror
  <button type="submit" class="btn btn-primary">Xác nhận</button>
</form>
@endsection     