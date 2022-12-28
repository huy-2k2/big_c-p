@extends('layouts.admin')
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

  <table class="table">
    <caption>List of products</caption>
    <thead>
      <tr>
        <th scope="col">Id sản phẩm</th> 
        <th scope="col">Lô hàng</th>
        <th scope="col">Dòng sản phẩm</th>
        <th scope="col">Trạng thái</th>
        <th scope="col">Số lần bảo hành</th>
        <th scope="col">Nhà máy sản xuất</th>
        <th scope="col">Đại lý</th>
        <th scope="col">Trung tâm bảo hành</th>
        <th scope="col">Người mua</th>
        <th scope="col">Thời gian mua</th>
        <th scope="col">Hạn bảo hành</th>
        <th scope="col">Còn hạn bảo hành?</th>
        <th scope="col">Bị thu hồi?</th>
      </tr>
    </thead>
    <tbody id="myTable">
      @foreach($products as $product) 
        <tr>
          <td>{{ $product -> id }}</td>
          <td>{{ $product -> batch_id }}</td>
          <td>{{ $ranges[$product->id] }}</td>
          <td>{{ $status[$product->id] }}</td>
          <td>{{ $product -> warranty_count }}</td>
          <td>{{ $factories[$product->id] }}</td>
          <td>{{ $agents[$product->id] }}</td>
          <td>{{ $warranties[$product->id] }}</td>
          <td>{{ $customers[$product->id] }}</td>
          <td>{{ $product->customer_buy_time }}</td>
          <td>{{ $product->end_date }}</td>
          <td>{{ $out_of_warranty[$product->id] }}</td>
          <td>{{ $product->is_recall ? 'Đang bị thu hồi' : 'Không bị thu hồi' }}</td>
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