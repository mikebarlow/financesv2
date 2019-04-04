<div class="alert-wrapper">
    @if (session('successMsg'))
        @include('alerts.success', ['message' => session('successMsg')])
    @elseif (! empty($successMsg))
        @include('alerts.success', ['message' => $successMsg])
    @endif

    @if (session('errorMsg'))
        @include('alerts.danger', ['message' => session('errorMsg')])
    @elseif (! empty($errorMsg))
        @include('alerts.danger', ['message' => $errorMsg])
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>