@extends('layouts.agent')
@section('content')
    @php
        $tbody = []
    @endphp
    @foreach ($depots as $depot)
        @php
            $tbody[] = [
              'data-id' => $depot->id,
              $depot->id,
              "<input data-id='$depot->id' data-origin='$depot->depot_name' class='depot-input depot-name-$depot->id px-2 py-1 border border-transparent focus:outline-none focus:border-blue-600' value='$depot->depot_name'/>",
              "<input data-id='$depot->id' data-origin='$depot->size' class='depot-input depot-size-$depot->id px-2 py-1 border border-transparent focus:outline-none focus:border-blue-600' value='$depot->size'/>",
              $depot->status_b ? 'còn trống': 'đã đầy',
              ['title' => 'thay đổi', 'class' => 'font-medium hover:underline text-blue-600 cursor-pointer alter_btn'],
              ['title' => 'hoàn tác', 'class' => "refresh-$depot->id font-medium hover:underline text-gray-600 cursor-pointer pointer-events-none refresh_btn"],
            ]
        @endphp
    @endforeach
  <div class="relative overflow-x-auto shadow-md sm:rounded-lg custom-scrollbar">
    @include('components.table', ['title' => 'kho chứa', 'ths' => ['id',  'tên', 'kích cỡ','trạng thái', ['title' => 'thay đổi', 'sr_only' => true], ['title' => 'hoàn tác', 'sr_only' => true]], 'tbody' => $tbody])
  </div>
  <script>
     (() => {
            const alter_btns = document.querySelectorAll('.alter_btn')
            const refresh_btns = document.querySelectorAll('.refresh_btn')
            const depot_inputs = document.querySelectorAll('.depot-input');
            depot_inputs.forEach(input => {
                input.onkeyup = function() {
                    const id = this.getAttribute('data-id')
                    const name = document.querySelector(`.depot-name-${id}`)
                    const size = document.querySelector(`.depot-size-${id}`)
                    const refresh_btn = document.querySelector(`.refresh-${id}`)
                    if(name.value == name.getAttribute('data-origin') && size.value == size.getAttribute('data-origin')) {
                        refresh_btn.classList.remove('active')
                    } else {
                        console.log(456);
                        refresh_btn.classList.add('active')
                    }
                }
            })
            refresh_btns.forEach(btn => {
                btn.onclick = function() {
                    const id = this.getAttribute('data-id')
                    const name = document.querySelector(`.depot-name-${id}`)
                    const size = document.querySelector(`.depot-size-${id}`)
                    name.value = name.getAttribute('data-origin')
                    size.value = size.getAttribute('data-origin')
                    this.classList.remove('active')
                }
            })
            alter_btns.forEach(btn => {
                btn.onclick = function() {
                    const id = this.getAttribute('data-id')
                    const name = document.querySelector(`.depot-name-${id}`)
                    const size = document.querySelector(`.depot-size-${id}`)
                    axios.post("{{ route('agent.put_edit_agent_depot')}}", {
                        access_token: getCookie('access_token'),
                        'user_id': '{{ Auth::user()->id }}',
                        'depot_id': id ,
                        'depot_name': name.value,
                        'size': size.value,
                    }).then(({data}) => {
                        name.setAttribute('data-origin', name.value)
                        size.setAttribute('data-origin', size.value)
                        document.querySelector(`.refresh-${id}`).classList.remove('active')
                        toastr.options = {
                                "closeButton" : true,
                                "progressBar" : true
                        }
                        toastr.success('thay đổi kho thành công');
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