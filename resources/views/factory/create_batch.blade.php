@extends('layouts.factory')
@section('content')
  <form method="post" action='{{ route('factory.create_batch_post') }}'>
    @csrf
    <div class="form-group">
      <label for="range">Dòng sản phẩm</label>
      <select class="form-control" id="range" name="range">   
        @foreach($ranges as $range) {
          <option value="{{ $range->id }}">{{ $range->name }}</option>
        }
        @endforeach
      </select>
      @error('range')
        <span style="color:red">{{ $message }}</span>
      @enderror
    </div>
    <div class="form-group">
      <label for="quantity">Số lượng</label>
      <input type="number" class="form-control" id="quantity" name="quantity">
      @error('quantity')
        <span style="color:red">{{ $message }}</span>
      @enderror
    </div>
    <div class="form-group">
      <label for="depot">Kho chứa</label>
      <select class="form-control" id="depot" name="depot">   
        @foreach($depots as $depot) {
          <option value="{{ $depot->id }}">{{ $depot->depot_name }}</option>
        }
        @endforeach
      </select>
      @error('depot')
        <span style="color:red">{{ $message }}</span>
      @enderror
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
@endsection