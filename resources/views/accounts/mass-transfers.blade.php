@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col">
            @include('alerts.main')

            <a href="{{ route('accounts.view', ['id' => $account->id]) }}" class="btn btn-primary mb-3">
                Back to account
            </a>

            <div class="card">
                <div class="card-header">
                    <div class="pull-left d-inline-block">{{ $account->name }} Mass Transfers</div>

                    <a href="{{ route('accounts.masstransfers.create', ['id' => $account->id]) }}" class="btn-card btn-card-right btn-success float-right">
                        Create
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
                            @forelse($account->massTransfers as $transfer)
                                <tr>
                                    <td>{{ $transfer->name }}</td>
                                    <td>
                                        <a href="{{ route('accounts.masstransfers.edit', ['id' => $transfer->id]) }}" class="btn btn-primary pull-left mr-3">Edit</a>

                                        <a href="{{ route('accounts.masstransfers.delete', ['id' => $transfer->id]) }}" class="btn btn-danger pull-left mr-3" onclick="return confirm('Are you sure?');">Delete</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">
                                        You have no mass transfers created.
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
