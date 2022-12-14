@extends('layouts.factory')
@section('content')
{{-- <a href="{{ route('factory.add_factory_depot') }}" class="btn btn-primary"><i class="fa-solid fa-plus"></i>Thêm kho</a>
  <hr/>

  <div class="container row">
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
    </script> --}}
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
                    axios.post("{{ route('factory.put_edit_factory_depot')}}", {
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