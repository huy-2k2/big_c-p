@extends('layouts.factory')
@section('content')
        <div class="max-w-[100vw]">
            <form action="{{ route('factory.print_statistic_defective') }}" method="POST" class="w-[500px] max-w-full flex flex-col mx-auto gap-5 p-5 rounded-lg shadow-lg">
                @csrf
                <input type="checkbox" id="ranges" name="ranges" value="ranges">
                <label for="ranges"> Chọn theo dòng sản phẩm </label><br>
                <input type="checkbox" id="factories" name="factories" value="factories">
                <label for="factories"> Chọn theo cơ sở sản xuất </label><br>
                <input type="checkbox" id="agents" name="agents" value="agents">
                <label for="agents"> Chọn theo đại lý phân phối </label><br>
                @include('components.button_submit', ['text' => 'in thống kê'])
            </form>
        </div>
    @parent
@endsection     