@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col">
            @include('alerts.main')

            <div class="card">
                <div class="card-header">
                    <div class="pull-left d-inline-block">Accounts</div>

                    <a href="{{ route('accounts.create') }}" class="btn-card btn-card-right btn-primary float-right">
                        Create New Account
                    </a>
                </div>

                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($accounts as $account)
                                <tr>
                                    <td>{{ $budget->name }}</td>
                                    <td>
                                        <a href="{{ route('budgets.edit', ['id' => $budget->id]) }}" class="btn btn-primary pull-left mr-3">Edit</a>

                                        <form class="form-inline pull-left" method="post" action="{{ route('budgets.delete', ['id' => $budget->id]) }}">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you Sure?')">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2">
                                        You have no accounts created.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
