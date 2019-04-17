@extends('layouts.app')

@section('content')
<view-sheet :accountid="{{ $account->id }}" inline-template>
    <div class="container">
        <div class="row">
            <div class="col">
                <h1>{{ $account->name }}</h1>
            </div>
        </div>

        <div class="row">
            @include('alerts.main')
        </div>

        <div class="row justify-content-center d-print-none">
            @include('accounts.partial.pay')
            @include('accounts.partial.transfer')
            @include('accounts.partial.multi-transfers')
        </div>
        <div class="row justify-content-center">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <div class="pull-left d-inline-block">
                            Sheet Beginning: {{ $account->latestSheet->start_date->format('jS M Y') }}
                            (<a href="{{ route('accounts.list-old-sheets', ['id' => $account->id]) }}">View older sheets</a>)
                        </div>

                        <button type="submit" class="d-print-none btn-card btn-card-right btn-danger float-right" onclick="javascript: window.print()">
                            <i class="fa fa-print"></i> Print
                        </button>
                    </div>

                    <div class="card-body p-0">
                        <table class="table table-striped mb-0">
                            <thead class="thead-dark">
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>Budgeted</th>
                                    <th>B/F</th>
                                    <th>Paid</th>
                                    <th>T/F In</th>
                                    <th>T/F Out</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tfoot class="d-print-none">
                                <tr>
                                    <td colspan="7">
                                        <form class="form-inline">

                                            <label>Sheet Completed On</label>
                                            <input type="text" name="sheet_end" class="form-control datepicker mx-3" v-model="end_date">

                                            <button class="d-print-none btn btn-success" @click.prevent="completeSheet">
                                                Complete Sheet
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            </tfoot>
                            <tbody v-if="account">
                                <tr v-for="row in account.latest.rows">
                                    <th>@{{ row.label }}</th>
                                    <td>&pound;@{{ row.budget }}</td>
                                    <td>&pound;@{{ row.broughtForward }}</td>
                                    <td>&pound;@{{ row.payments }}</td>
                                    <td>&pound;@{{ row.transIn }}</td>
                                    <td>&pound;@{{ row.transOut }}</td>
                                    <td>&pound;@{{ row.total }}</td>
                                </tr>

                                <tr>
                                    <th>Totals</th>
                                    <td>&pound;@{{ account.latest.totals.budget }}</td>
                                    <td>&pound;@{{ account.latest.totals.broughtForward }}</td>
                                    <td>&pound;@{{ account.latest.totals.payments }}</td>
                                    <td>&pound;@{{ account.latest.totals.transIn }}</td>
                                    <td>&pound;@{{ account.latest.totals.transOut }}</td>
                                    <td v-if="account.latest.totalsMatch" class="table-success">
                                        &pound;@{{ account.latest.totals.totDown }}
                                    </td>
                                    <td v-else class="table-danger">
                                        A: &pound;@{{ account.latest.totals.totAcross }}<br>
                                        D: &pound;@{{ account.latest.totals.totDown }}
                                    </td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="7">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-4 d-print-none">
                <div class="card">
                    <div class="card-header">
                        <div class="pull-left d-inline-block">
                            Transactions
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <table class="table table-striped mb-0">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Transaction</th>
                                    <th>Amount</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="transaction in transactions">
                                    <td>@{{ transaction.label }}</td>
                                    <td>&pound;@{{ transaction.amount }}</td>
                                    <td>
                                        <button class="btn btn-danger btn-xs " @click.prevent="deleteTransaction($event, transaction.id)">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</view-sheet>
@endsection
