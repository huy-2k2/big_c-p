@extends('layouts.admin')
@section('content')
<form method="GET" action='{{ route('admin.post_new_batch_recall') }}'>
  <div class="form-group">
    <label for="batch_id">Mã lô hàng</label>
    <select name="batch_id" id="batch_id">
      @foreach($batch_id as $id)
        <option value="{{ $id -> batch_id}}">{{ $id->batch_id }}</option>
      @endforeach
    </select>
  </div>
  @error('batch_id')
    <span style="color:red">{{ $message }}</span>
  @enderror
  <button type="submit" class="btn btn-primary">Xác nhận</button>
</form>
@endsection     