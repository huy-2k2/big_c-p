@extends('layouts.factory')
@section('content')
<div id="search_block" class="panel-body">
  <form class="form-horizontal" method="POST" action='{{ route('factory.post_transfer_prod_to_agent') }}'>
      @csrf
      <div class="form-group row">
          <label class="col-xs-10 col-sm-2 col-md-1 control-label" for="quantity_prod">Số lượng:</label>
          <div class="col-xs-10 col-sm-8 col-md-4">
              <input type="number" placeholder="Nhập số lượng sản phẩm" name="quantity_prod" id="quantity_prod" class="form-control">
          </div>
          @error('quantity_prod')
            <span style="color:red">{{ $message }}</span>
          @enderror
      </div>
      <div class="form-group row">
          <label class="col-xs-10 col-sm-2 col-md-1 control-label">Dòng sản phẩm:</label>
          <div class="col-xs-10 col-xs-offset-0 col-sm-8 col-sm-offset-2 col-md-2 col-md-offset-0">
            <select class="form-control" name="range" id="range">
                @foreach($lines as $line) {
                  <option value={{ $line -> id }}>{{ $line -> name }}</option>
                }
                @endforeach
            </select>
          </div>
          @error('range')
            <span style="color:red">{{ $message }}</span>
          @enderror
      </div>
      <div class="form-group row">
        <label class="col-xs-10 col-sm-2 col-md-1 control-label">Đại lý:</label>
        <div class="col-xs-10 col-xs-offset-0 col-sm-8 col-sm-offset-2 col-md-2 col-md-offset-0">
          <select class="form-control" name="agent" id="agent">
              @foreach($agents as $agent) {
                <option value={{ $agent -> user_id }}>{{ (DB::table('users') -> where('id', '=', $agent -> user_id) -> first()) -> name }}</option>
              }
              @endforeach
          </select>
        </div>
        @error('agent')
            <span style="color:red">{{ $message }}</span>
          @enderror
    </div>

    <button class="btn btn-success" type="submit">Xuất kho</button>
  </form>
</div>

@endsection