@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col">
            @include('alerts.main')

            <div class="card">
                <div class="card-header">
                    <div class="pull-left d-inline-block">Account Budgets</div>

                    <a href="{{ route('budgets.create') }}" class="btn-card btn-card-right btn-primary float-right">
                        Create New Budget
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
                            @forelse($budgets as $budget)
                                <tr>
                                    <td>{{ $budget->name }}</td>
                                    <td>
                                        <a href="#" class="btn btn-primary">Edit</a>

                                        <form class="form-inline" method="post" action="{{ route('budgets.delete', ['id' => $budget->id]) }}">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-danger">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2">
                                        There are no budgets.
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
