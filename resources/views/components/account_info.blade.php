<div class="grid w-full gap-5 grid-col-1 lg:grid-cols-2">
    @include('components.input_float_disable', ['value' => $name, 'label' => 'họ và tên'])
    @include('components.input_float_disable', ['value' => $email, 'label' => 'email'])
    @include('components.input_float_disable', ['value' => $role, 'label' => 'vai trò'])
    @include('components.input_float_disable', ['value' => $province, 'label' => 'tỉnh / thành phố'])
    @include('components.input_float_disable', ['value' => $district, 'label' => 'quận / huyện'])
    @include('components.input_float_disable', ['value' => $sub_district, 'label' => 'xã / phường'])
    @include('components.input_float_disable', ['value' => $created_at, 'label' => 'thời gian tạo tài khoản'])
    @include('components.input_float_disable', ['value' => $account_accepted_at, 'label' => 'thời gian được chấp thuận'])
 </div>