@extends('layouts.factory')
@section('content')
<a href="{{ route('factory.add_factory_depot') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i>Thêm kho</a>
  <hr/>

  <div class="row container">
    <form class="d-flex">
      <input id="myInput" class="form-control me-2" type="search" 
      placeholder="Search by name" aria-label="Search" 
      name="keywords" value="{{ request() -> keywords }}">
      <button class="btn btn-success" type="submit">Search</button>
    </form>
  </div>

  <table class="table table-striped table-hover">
    <thead>
      <th>Id</th>
      <th>Tên kho</th>
      <th>Kích thước</th>
      <th>Trạng thái</th>
    </thead>
    <tbody id='myTable'>
      @foreach ($depots as $depot)
        <tr>
          <td>{{ $depot -> id }}</td>
          <td>{{ $depot -> depot_name }}</td>
          <td>{{ $depot -> size }}</td>
          <td>{{ $depot -> status_b ? 'Trống' : 'Đầy' }}</td>
          <td><button class="btn btn-info"><a href="{{ route('factory.edit_factory_depot', ['id' => $depot->id]) }}">Sửa</a></button></td>
          <td><button class="btn btn-danger"><a href="{{ route('factory.delete_factory_depot', ['id' => $depot->id]) }}">Xóa</a></button></td>
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