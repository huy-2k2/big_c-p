@extends('layouts.factory')
@section('content')
{{-- <div id="search_block" class="panel-body">
  <form class="form-horizontal" method="POST" action='{{ route('factory.post_transfer_prod_to_agent') }}'>
      @csrf
      <div class="form-group row">
          <label class="col-xs-10 col-sm-2 col-md-1 control-label" for="quantity_prod">Số lượngg:</label>
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
                @foreach($lines as $line) 
                  <option value={{ $line -> id }}>{{ $line -> name }}</option>
                
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
              @foreach($agents as $agent) 
                <option value={{ $agent -> user_id }}>{{ (DB::table('users') -> where('id', '=', $agent -> user_id) -> first()) -> name }}</option>
              
              @endforeach
          </select>
        </div>
        @error('agent')
            <span style="color:red">{{ $message }}</span>
          @enderror
    </div>

    <button class="btn btn-success" type="submit">Xuất kho</button>
  </form>
</div> --}}
@php
    $range_options = [];
    $agent_options = [];
@endphp
@foreach ($ranges as $range)
    @php
        $range_options[] = [
          'value' => $range->id,
          'title' => $range->name
        ]
    @endphp
@endforeach
@foreach ($agents as $agent)
    @php
        $agent_options[] = [
          'value' => $agent->user_id,
          'title' => $agent->user->name
        ]
    @endphp
@endforeach
<div class="max-w-[100vw]">
  <form action="{{ route('factory.post_transfer_prod_to_agent') }}" method="POST" class="w-[500px] max-w-full flex flex-col mx-auto gap-5 p-5 rounded-lg shadow-lg">
      @csrf
      
      @include('components.input', ['name' => 'quantity_prod', 'label' => 'số lượng'])
      @include('components.input_select', ['name' => 'range', 'label' => 'dòng sản phẩm', 'options' => $range_options])
      @include('components.input_select', ['name' => 'agent', 'label' => 'đại lý', 'options' => $agent_options])
      @include('components.button_submit', ['text' => 'xuất kho'])
  </form>
</div>
@endsection