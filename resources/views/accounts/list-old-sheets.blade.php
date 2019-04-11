@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col">
            @include('alerts.main')

            <div class="card">
                <div class="card-header">
                    <div class="pull-left d-inline-block">{{ $account->name }} Sheets</div>

                    <a href="{{ route('accounts.view', ['id' => $account->id]) }}" class="btn-card btn-card-right btn-primary float-right">
                        View Latest Sheet
                    </a>
                </div>

                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach ($account->sheets as $sheet)
                            <a href="{{ route('accounts.view-old-sheet', ['id' => $account->id, 'sheetId' => $sheet->id]) }}" class="list-group-item list-group-item-action">
                                {{ $sheet->start_date->format('jS M Y') }} {{ $sheet->end_date !== null ? ' - ' . $sheet->end_date->format('jS M Y') : ''}}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
