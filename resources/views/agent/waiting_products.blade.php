@extends('layouts.agent')
@section('content')
<div id="search_block" class="panel-body">
    <form>
        <div class="form-group row">
            <label class="col-xs-10 col-sm-2 col-md-1 control-label" for="myInput`">Search:</label>
            <div class="col-xs-10 col-sm-8 col-md-4">
                <input type="text" placeholder="Enter Search Keywords" value="" name="myInput`" id="myInput" class="form-control">
            </div>
        </div>
    </form>
  </div>
  
  <table class="table table-striped table-hover">
    <thead>
      <th>Số lô</th>
      <th>Dòng sản phẩm</th>
      <th>Số lượng</th>
      <th>Ngày nhận</th>
      <th>Chọn kho nhập</th>
      <th>Số lượng nhập kho</th>
    </thead>
    <tbody id='myTable'>
        @foreach ($arr_transfer_batch as $one_transfer_batch)
            <form method="GET" action={{ route('agent.transfer_to_depot') }}>
            <tr>
                <td><input style="display:none" name="transfer_batch" value={{ $one_transfer_batch['transfer_batch'] }}>{{ $one_transfer_batch['transfer_batch'] }}</td>
                
                <td>{{ (DB::table('ranges')->where('id', $one_transfer_batch['range_id'])->first())->name }}</td>
                <td><input style="display:none" name="quantity" value={{ $one_transfer_batch['quantity'] }}>{{ $one_transfer_batch['quantity'] }}</td>
                <td>{{ $one_transfer_batch['created_at'] }}</td>
                <td><select class="form-control" name="depot" id="depot">
                    @foreach($depots as $depot) {
                      <option value={{ $depot -> id }}>{{ $depot -> depot_name }}</option>
                    }
                    @endforeach
                </select></td>
                <td><input type="number" class="form-control" id="quantity_to_depot" name="quantity_to_depot"></td>
                <td><button class="btn btn-danger" type="submit">Nhập kho</button></td>
            </tr>
            </form>
        @endforeach
        @error('quantity_to_depot')
                    <span style="color:red">{{ $message }}</span>
            @enderror
    </tbody>
  </table>
  
  <script>
    $(document).ready(function(){
      $("#myInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#myTable tr").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
      });
    });
  </script>
@endsection