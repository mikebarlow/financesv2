@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col">
            <end-sheet :accountid="{{ $account->id }}" inline-template>
                <div class="card">
                    <div class="card-header">
                        <div class="pull-left d-inline-block">Complete Sheet for {{ $account->name }}</div>

                        <button type="submit" class="btn-card btn-card-right btn-success float-right" @click.prevent="saveSheet">
                            Complete
                        </button>
                    </div>

                    <div class="card-body">
                        @include('alerts.main')

                        <div class="form-group">
                            <label>Sheet Completed On</label>
                            <input type="text" name="sheet_end" class="form-control datepicker" v-model="end_date">
                        </div>
                    </div>
                </div>
            </end-sheet>
        </div>
    </div>
</div>
@endsection
