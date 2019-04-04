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
                                <th>Latest Sheet</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($accounts as $account)
                                <tr>
                                    <td>{{ $account->name }}</td>
                                    <td>--</td>
                                    <td>
                                        @if ($account->latestSheet !== null)
                                            <a href="{{ route('accounts.view', ['id' => $account->id]) }}" class="btn btn-success pull-left mr-3">View</a>
                                        @else
                                            <a href="{{ route('accounts.start', ['id' => $account->id]) }}" class="btn btn-info pull-left mr-3">Start</a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">
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
