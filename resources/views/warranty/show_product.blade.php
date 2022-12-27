@extends('layouts.warranty')
@section('content')
<div id="search_block" class="panel-body">
  <form>
      <div class="form-group row">
          <label class="col-xs-10 col-sm-2 col-md-1 control-label" for="myInput">Search:</label>
          <div class="col-xs-10 col-sm-8 col-md-4">
              <input type="text" placeholder="Enter Search Keywords" value="" name="myInput" id="myInput" class="form-control">
          </div>
      </div>
  </form>
</div>

<table class="table table-striped table-hover">
  <thead>
    <th>Id sản phẩm</th>
    <th>Id lô sản phẩm</th>
    <th>Dòng sản phẩm</th>
    <th>Số lần bảo hành</th>
    <th>Lỗi</th>
    <th>Chi tiết lý do</th>
    <th>Chức năng</th>
  </thead>
  <tbody id='myTable'>
    @foreach ($products as $product)
    <tr>
      <td>{{ $product -> id }}</td>
      <td>{{ $product -> batch_id }}</td>
      <td>{{ (DB::table('ranges')->where('id', $product->range_id)->first())->name }}</td>
      <td>{{ $product -> warranty_count }}</td>
      <td>{{ $error_name[$product->id] }}</td>
      <td>{{ $reasons[$product->id] }}</td>
      <td><button class="btn btn-success"><a href="{{ route('warranty.return_prod_to_agent', ['id'=>$product->id]) }}">Bảo hành xong</a></button></td>
      <td><button class="btn btn-danger"><a href="{{ route('warranty.return_prod_to_factory', ['id'=>$product->id]) }}">Chuyển về CSSX</a></button></td>
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
</script>
@endsection