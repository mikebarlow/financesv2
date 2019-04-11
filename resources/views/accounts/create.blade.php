@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col">
            <form method="post" action="{{ route('accounts.create.post') }}">
                @csrf

                <div class="card">
                    <div class="card-header">
                        <div class="pull-left d-inline-block">Create New Account</div>

                        <button type="submit" class="btn-card btn-card-right btn-success float-right">
                            Save
                        </button>
                    </div>

                    <div class="card-body">
                        @include('alerts.main')

                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Budget</label>
                            <select name="budget_id" class="form-control">
                                <option>Select Budget</option>
                                @foreach ($budgets as $budget)
                                    <option value="{{ $budget->id }}">{{ $budget->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        @foreach ($users as $user)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" name="share" id="defaultCheck1">
                                <label class="form-check-label" for="defaultCheck1">
                                    Share account with {{ $user->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
