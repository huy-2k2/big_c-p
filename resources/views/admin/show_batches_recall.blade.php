@extends('layouts.admin')
@section('content')
  @php
      $tbody = []
  @endphp
  @foreach ($batches as $batch)
      @php
          $tbody[] = [
            'data-id' => $batch->id,
            $batch->id,
            $batch->range->name,
            ['title' => $batch->factory->user->name, 'data-id' => $batch->factory_id, 'class' => 'open-user-detail-btn underline cursor-pointer'],
            $batch->manufacturing_date,
            $batch->quantity,
            ['title' => 'Bảo hành xong, trả về', 'class' => 'warranty-done font-medium text-blue-600 cursor-pointer hover:underline']
          ]
      @endphp
  @endforeach
  <div class="relative overflow-x-auto shadow-md sm:rounded-lg custom-scrollbar">
    @include('components.table', ['title' => 'Lô đang thu hồi', 'ths' => ['id', 'dòng sản phẩm', 'nơi sản xuất', 'ngày sản xuất', 'số lượng', ['title' => 'Bảo hành xong, trả về', 'sr_only' => true]], 'tbody' => $tbody])
  </div>
  <script>
    (() => {
      const warranted_btns = document.querySelectorAll('.warranty-done')
      warranted_btns.forEach(btn => {
        btn.onclick = function() {
          handle_remove_submited(this)
          const batch_id = this.getAttribute('data-id')
          axios.post('{{ route('admin.return_batch_recall')}}', {
            access_token: getCookie('access_token'),
            user_id: '{{ Auth::user()->id }}',
            batch_id: batch_id
          })
          .then(({data}) => {
              toastr.options = {
                "closeButton" : true,
                "progressBar" : true
              }
              toastr.success(data.message);
              console.log(data.message);
          })
        }
      });
      
    })()
  </script>
  @parent
@endsection     