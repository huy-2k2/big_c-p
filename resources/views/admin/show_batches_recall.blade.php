@extends('layouts.admin')
@section('content')
<table class="table table-striped table-hover">
    <thead>
      <th>Id lô sản phẩm</th>
      <th>Dòng sản phẩm</th>
      <th>Nhà máy sản xuất</th>
      <th>Ngày sản xuất</th>
      <th>Số lượng</th>
    </thead>
    <tbody id='myTable'>
      @foreach ($batches as $batch)
      <tr>
        <td>{{ $batch -> id }}</td>
        <td>{{ (DB::table('ranges')->where('id', $batch->range_id)->first())->name }}</td>
        <td>{{ $batch -> factory_id }}</td>
        <td>{{ $batch -> manufacturing_date }}</td>
        <td>{{ $batch-> quantity }}</td>
        <td><button class="btn btn-success"><a href="{{ route('admin.return_batch_recall', ['id'=>$batch->id]) }}">Bảo hành xong, trả về</a></button></td>
      </tr>
      @endforeach
  
    </tbody>
  </table>
@endsection     