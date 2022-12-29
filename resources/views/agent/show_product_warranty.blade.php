@extends('layouts.agent')
@section('content')
@php
    $tbody = [];
    $options = [];
@endphp

@foreach ($warranties as $warranty)
  @php
      $options[] = [
        'title' => $warranty->name,
        'value' => $warranty->id,
      ]
  @endphp
@endforeach

@foreach ($products_to_customer as $product)
    @php
        $tbody[] = [
          'data-id' => $product->id,
          $product->id,
          (DB::table('users')->where('id', $product->customer_id)->first())->name,
          'Bảo hành xong',
          (DB::table('users')->where('id', $product->warranty_id)->first())->name,
          ['title' => 'trả người dùng', 'class' => "return-customer font-medium hover:underline text-blue-600 cursor-pointer"],
        ]
    @endphp
@endforeach

@foreach ($products_to_warranty as $product)
    @php
        $tbody[] = [
          'data-id' => $product->id,
          $product->id,
          (DB::table('users')->where('id', $product->customer_id)->first())->name,
          'Đang chờ bảo hành',
          'component' => ['name' => 'components.input_select', 'param' => ['name' => 'warranty_id', 'label' => 'chọn nơi bảo hành', 'options' => $options], 'class' => "warranty-$product->id min-w-[220px]"],
          ['title' => 'bảo hành', 'class' => "warrant-product font-medium hover:underline text-blue-600 cursor-pointer"],
        ]
    @endphp
@endforeach

<div class="relative overflow-x-auto shadow-md sm:rounded-lg custom-scrollbar">
  @include('components.table', ['title' => 'Bảo hành', 'ths' => ['id sản phẩm',  'người dùng sở hữu', 'trạng thái','trung tâm bảo hành', ['title' => 'chức năng', 'sr_only' => true]], 'tbody' => $tbody])
</div>

<script>
  (() => {
    const warrant_btns = document.querySelectorAll('.warrant-product');
    const return_btns = document.querySelectorAll('.return-customer');

    return_btns.forEach(btn => {
      btn.onclick = function() {
        const id = this.getAttribute('data-id');
        axios.post("{{ route('agent.transfer_error_prod_return_to_customer') }}", {
                        'access_token': getCookie('access_token'),
                        'user_id': '{{ Auth::user()->id }}',
                        'product_id': id,
        }).then(({data})=> {
          if(data.type == 'success') {
            const table_body = document.querySelector('tbody')
            table_body.removeChild(btn.parentElement)
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
    warrant_btns.forEach(btn => {
      btn.onclick = function() {
        const id = this.getAttribute('data-id');
        const warranty_id = document.querySelector(`.warranty-${id} select`).value
        axios.post("{{ route('agent.transfer_error_prod_to_warranty') }}", {
            'access_token': getCookie('access_token'),
            'user_id': '{{ Auth::user()->id }}',
            'product_id': id,
            'warranty_id': warranty_id
        }).then(({data})=> {
          if(data.type == 'success') {
            const table_body = document.querySelector('tbody')
            table_body.removeChild(btn.parentElement)
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
