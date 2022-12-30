@extends('layouts.agent')
@section('content')
{{-- <div id="search_block" class="panel-body">
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
                    @foreach($depots as $depot) 
                      <option value={{ $depot -> id }}>{{ $depot -> depot_name }}</option>
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
  </script> --}}
@php
    $tbody = [];
    $options = [];
@endphp
@foreach ($depots as $depot)
    @php
        $options[] = [
          'title' => $depot->depot_name,
          'value' => $depot->id
        ]
    @endphp
@endforeach
@foreach ($arr_transfer_batch as $one_transfer_batch)
    @php
        $key = $one_transfer_batch['transfer_batch'];
        $tbody[] = [
          'data-id' => $key,
          $one_transfer_batch['transfer_batch'],
          (DB::table('ranges')->where('id', $one_transfer_batch['range_id'])->first())->name,
          ['title' =>  $one_transfer_batch['quantity'], 'class' => "total-quantity-$key"],
          $one_transfer_batch['created_at'],
          'component' => ['name' => 'components.input_select', 'param' => ['name' => 'depot', 'label' => 'chọn kho', 'options' => $options], 'class' => "depot-$key min-w-[250px]"],
          "<input name='quantity' class='quantity-$key px-2 py-1 border border-blue-600 quantity-input focus:outline-none' />",
          ['title' => 'nhập kho', 'class' => "import-btn font-medium hover:underline text-blue-600 cursor-pointer"],
        ]
        
    @endphp
@endforeach
<div class="relative overflow-x-auto shadow-md sm:rounded-lg custom-scrollbar">
  @include('components.table', ['title' => 'xuất kho', 'ths' => ['số lô',  'tên dòng sản phẩm', 'số lượng','ngày nhập', 'chọn kho nhập', 'chọn số lượng', ['title' => 'nhập kho', 'sr_only' => true]], 'tbody' => $tbody])
</div>
<script>
  (() => {
    const import_btns = document.querySelectorAll('.import-btn')
    import_btns.forEach(btn => {
      btn.onclick = function() {
        const id = this.getAttribute('data-id')
        const quantity = document.querySelector(`.quantity-${id}`).value
        const depot = document.querySelector(`.depot-${id} select`).value
        const total_quantity = document.querySelector(`.total-quantity-${id}`).innerText
        axios.post("{{ route('agent.transfer_to_depot') }}", {
                        'access_token': getCookie('access_token'),
                        'user_id': '{{ Auth::user()->id }}',
                        'transfer_batch': id ,
                        'depot': depot,
                        'quantity': total_quantity,
                        'quantity_to_depot': quantity
                    }).then(({data}) => {
                      if(data.type == 'success') {
                        if(total_quantity - quantity) {
                          document.querySelector(`.total-quantity-${id}`).innerText = total_quantity - quantity
                        } else {
                          handle_remove_submited(btn)
                        }
                      }
                      toastr.options = {
                                "closeButton" : true,
                                "progressBar" : true
                            }
                            toastr[data.type](data.message);
                    }).catch(({response: {data: {errors}}}) => {
                        for (const key in errors) {
                            toastr.options = {
                                "closeButton" : true,
                                "progressBar" : true
                            }
                            toastr.error(errors[key][0]);
                        }
                    })
      }
    });
  })()
</script>
@endsection