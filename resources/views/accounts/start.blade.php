@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col">
            <start-sheet :accountid="{{ $account->id }}" inline-template>
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
                            <label>Sheet Starts On</label>
                            <input type="text" name="sheet_start" class="form-control datepicker">
                        </div>

                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Label</th>
                                    <th>Budget</th>
                                    <th>Brought Forward</th>
                                </tr>
                            </thead>
                            <tbody v-if="rows">
                                <tr v-for="(row, key) in rows">
                                    <td>@{{ row.name }}</td>
                                    <td>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">&pound;</span>
                                            </div>
                                            <input type="number" name="budget" class="form-control" placeholder="100.50" v-model="rows[key].amount">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">&pound;</span>
                                            </div>
                                            <input type="number" name="amount" class="form-control" placeholder="100.50" v-model="bfRows[key].amount">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Total</th>
                                    <td>&pound;@{{ budgettotal | currency(2) }}</td>
                                    <td>&pound;@{{ bftotal | currency(2) }}</td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="3">
                                        Add rows to your budget
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </start-sheet>
        </div>
    </div>
</div>
@endsection
