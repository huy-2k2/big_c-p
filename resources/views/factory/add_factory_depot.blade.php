@extends('layouts.factory')
@section('content')
  <form method="POST" action='{{ route('factory.post_add_factory_depot') }}'>
    @csrf
    <div class="form-group">
      <label for="depot_name">Tên kho</label>
      <input type="text" class="form-control" id="depot_name" name="depot_name" value="{{ old('depot_name') }}">
      @error('depot_name')
        <span style="color:red">{{ $message }}</span>
      @enderror
    </div>
    <div class="form-group">
      <label for="size">Kích thước</label>
      <input type="number" class="form-control" id="size" name="size">
      @error('size')
        <span style="color:red">{{ $message }}</span>
      @enderror
    </div>
    <button type="submit" class="btn btn-primary">Tạo mới</button>
  </form>
@endsection