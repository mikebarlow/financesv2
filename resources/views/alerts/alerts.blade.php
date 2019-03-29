@if (session('successMsg'))
    @include('alerts.success', ['message' => session('successMsg')])
@endif
@if (session('errorMsg'))
    @include('alerts.danger', ['message' => session('errorMsg')])
@endif
