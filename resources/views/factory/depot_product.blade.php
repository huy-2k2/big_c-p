@extends('layouts.factory')
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
            <select class="form-control" name="range" id="range">
                <option value="all">Tất cả dòng sản phẩm</option>
                @foreach($lines as $line) {
                  <option value={{ $line -> id }}>{{ $line -> name }}</option>
                }
                @endforeach
            </select>
          </div>

          <div class="col-xs-10 col-sm-8 col-md-2">
              <select class="form-control" name="depot" id="depot">
                  <option value="all">Tất cả kho</option>
                  @foreach($depots as $depot) {
                    <option value={{ $depot -> id }}>{{ $depot -> depot_name }}</option>
                  }
                  @endforeach
              </select>
          </div>
      </div>
  </form>
</div>

<table class="table table-striped table-hover">
  <thead>
    <th>Dòng sản phẩm</th>
    <th>Kho</th>
    <th>Số lượng</th>
    <th>Kho còn trống</th>
  </thead>
  <tbody id='myTable'>
    @foreach ($lines as $line)
      @foreach($depots as $depot)
        <tr>
          <td value={{ $line->id }}>{{ $line -> name }}</td>
          <td value={{ $depot->id }}>{{ $depot -> depot_name }}</td>
          <td>{{ 
            App\Models\Product::count_quantity_product(['range_id', 'depot_id', 'status_id'], [$line->id, $depot->id, 1]);
          }}</td>
          <td value={{ $depot->id }}>{{ 
            App\Models\Product::count_quantity_product(['depot_id'], [$depot->id]);
          }} / {{ $depot -> size }}</td>
        </tr>
      @endforeach
    @endforeach
       
        {{-- <td><button class="btn btn-info"><a href="{{ route('factory.edit_factory_depot', ['id' => $depot->id]) }}">Sửa</a></button></td>
        <td><button class="btn btn-danger"><a href="{{ route('factory.delete_factory_depot', ['id' => $depot->id]) }}">Xóa</a></button></td> --}}

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
    $("#range").on("change", function() {
      var value_range = $(this).val();
      var value_depot = $("#depot").val();

      if(value_range == 'all' && value_depot == 'all') {
        $("#myTable tr").filter(function() {
          $(this).show();
        });
      } else if(value_range != 'all' && value_depot == 'all') {
          $("#myTable tr").filter(function() {
            $(this).toggle($(this).find("td:nth-child(2)").attr('value').indexOf(value_range) > -1);
          });
      } else if(value_range == 'all' && value_depot != 'all') {
          $("#myTable tr").filter(function() {
            $(this).toggle($(this).find("td:nth-child(3)").attr('value').indexOf(value_depot) > -1);
          });
      } else {
          $("#myTable tr").filter(function() {
            $(this).toggle($(this).find("td:nth-child(2)").attr('value').indexOf(value_range) > -1
                          && $(this).find("td:nth-child(3)").attr('value').indexOf(value_depot) > -1);
          });
      }
      
    });
  });

  $(document).ready(function(){
    $("#depot").on("change", function() {
      var value_depot = $(this).val();
      var value_range = $("#range").val();

      if(value_range == 'all' && value_depot == 'all') {
        $("#myTable tr").filter(function() {
          $(this).show();
        });
      } else if(value_range != 'all' && value_depot == 'all') {
          $("#myTable tr").filter(function() {
            $(this).toggle($(this).find("td:nth-child(2)").attr('value').indexOf(value_range) > -1);
          });
      } else if(value_range == 'all' && value_depot != 'all') {
          $("#myTable tr").filter(function() {
            $(this).toggle($(this).find("td:nth-child(3)").attr('value').indexOf(value_depot) > -1);
          });
      } else {
          $("#myTable tr").filter(function() {
            $(this).toggle($(this).find("td:nth-child(2)").attr('value').indexOf(value_range) > -1
                          && $(this).find("td:nth-child(3)").attr('value').indexOf(value_depot) > -1);
          });
      }
      
    });
  });
</script>
@endsection