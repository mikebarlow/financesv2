@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col">
            <form method="post" action="{{ route('accounts.create.post') }}">
                @csrf

                <div class="card">
                    <div class="card-header">
                        <div class="pull-left d-inline-block">Start "{{ $account->name }}"</div>

                        <button type="submit" class="btn-card btn-card-right btn-success float-right">
                            Start
                        </button>
                    </div>

                    <div class="card-body">
                        @include('alerts.main')

                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control">
                        </div>


                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
