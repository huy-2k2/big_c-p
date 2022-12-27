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
      <div class="form-group row">
          <label class="col-xs-10 col-sm-2 col-md-1 control-label">Lọc theo:</label>
          <div class="col-xs-10 col-xs-offset-0 col-sm-8 col-sm-offset-2 col-md-2 col-md-offset-0">
            <select class="form-control" name="status_product" id="status_product">
                <option value="all">Tất cả trạng thái bảo hành</option>
                <option value="4">Đang chờ bảo hành</option>
                <option value="7">Bảo hành xong</option>
            </select>
          </div>
      </div>
  </form>
</div>

<table class="table table-striped table-hover">
  <thead>
    <th>Id sản phẩm</th>
    <th>Người dùng sở hữu</th>
    <th>Trạng thái</th>
    <th>Trung tâm bảo hành</th>
    <th>Chức năng</th>
  </thead>
  <tbody id='myTable'>
    @foreach ($products_to_warranty as $product)
    <tr>
        <form method="POST" action={{ route('agent.transfer_error_prod_to_warranty') }}>
          @csrf
        <td><input style="display:none" name="product_id" value={{ $product->id }}>{{ $product -> id }}</td>
        <td>{{ (DB::table('users')->where('id', $product->customer_id)->first())->name }}</td>
        <td value="4">Đang chờ bảo hành</td>
        <td><select class="form-control" name="warranty_id" id="warranty_id">
          @foreach($warranties as $warranty) {
            <option value={{ $warranty -> id }}>{{ $warranty -> name }}</option>
          }
          @endforeach
        </select></td>
        <td><button class="btn btn-danger" type="submit">Chuyển đến TTBH</button></td>
        </form>
      </tr>
    @endforeach
       
    @foreach ($products_to_customer as $product)
    <tr>
        <form method="POST" action={{ route('agent.transfer_error_prod_return_to_customer') }}>
          @csrf
        <td><input style="display:none" name="product_id" value={{ $product->id }}>{{ $product -> id }}</td>
        <td>{{ (DB::table('users')->where('id', $product->customer_id)->first())->name }}</td>
        <td value="7">Bảo hành xong</td>
        <td>{{ $product -> warranty_id }}</td>
        <td><button class="btn btn-success" type="submit">Trả người dùng</button></td>
        </form>
      </tr>
    @endforeach

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

  $(document).ready(function(){
    $("#status_product").on("change", function() {
      var value_status_product = $(this).val();

      if(value_status_product == 'all') {
        $("#myTable tr").filter(function() {
          $(this).show();
        });
      } else {
          $("#myTable tr").filter(function() {
            $(this).toggle($(this).find("td:nth-child(3)").attr('value').indexOf(value_status_product) > -1);
          });
      }
    });
  });
</script>
@endsection