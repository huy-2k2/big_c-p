@extends('layouts.warranty')
@section('content')
@php
    $tbody = [];
@endphp
@foreach ($products as $product)
    @php
        $tbody[] = [
          'data-id' => $product->id,
          $product->id,
          $product->batch_id,
          (DB::table('ranges')->where('id', $product->range_id)->first())->name,
          $product->warranty_count,
          $error_name[$product->id],
          $reasons[$product->id],
          ['title' => 'bảo hành xong', 'class' => "warranted font-medium hover:underline text-blue-600 cursor-pointer"],
          ['title' => 'trả về nhà máy', 'class' => "return-factory font-medium hover:underline text-blue-600 cursor-pointer"],
        ]
    @endphp
@endforeach
<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
  @include('components.table', ['title' => 'Bảo hành', 'ths' => ['id sản phẩm',  'id lô sản phẩm', 'dòng sảm phẩm', 'số lần bảo hành','lỗi', 'chi tiết lỗi', ['title' => 'bảo hành xong', 'sr_only' => true], ['title' => 'bảo hành xong', 'sr_only' => true]], 'tbody' => $tbody])
</div>
<script>
  (() => {
    const warranted_btns = document.querySelectorAll('.warranted')
    const return_factory_btns = document.querySelectorAll('.return-factory')
    warranted_btns.forEach(btn => {
      btn.onclick = function() {
        console.log(123);
        const id = this.getAttribute('data-id')
        axios.post("{{route('warranty.return_prod_to_agent')}}", {
              'access_token': getCookie('access_token'),
              'user_id': '{{ Auth::user()->id }}',
              'product_id': id,
        }).then(({data})=> {
          if(data.type == 'success') {
            handle_remove_submited(btn)
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

    return_factory_btns.forEach(btn => {
      btn.onclick = function() {
        const id = this.getAttribute('data-id')
        axios.post("{{route('warranty.return_prod_to_factory')}}", {
              'access_token': getCookie('access_token'),
              'user_id': '{{ Auth::user()->id }}',
              'product_id': id,
        }).then(({data})=> {
          if(data.type == 'success') {
            handle_remove_submited(btn)
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