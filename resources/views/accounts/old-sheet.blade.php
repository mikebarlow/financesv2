@extends('layouts.app')

@section('content')
<view-old-sheet :accountid="{{ $account->id }}" :sheetid="{{ $sheet->id }}" inline-template>
    <div class="container">
        <div class="row">
            @include('alerts.main')
        </div>

        <div class="row justify-content-center">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <div class="pull-left d-inline-block">
                            Sheet: {{ $sheet->start_date->format('jS M Y') }} - {{ $sheet->end_date->format('jS M Y') }}
                            (<a href="{{ route('accounts.view', ['id' => $account->id]) }}">View latest sheet</a>)
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
                            <tbody v-if="account">
                                <tr v-for="row in account.sheet.rows">
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
                                    <td>&pound;@{{ account.sheet.totals.budget }}</td>
                                    <td>&pound;@{{ account.sheet.totals.broughtForward }}</td>
                                    <td>&pound;@{{ account.sheet.totals.payments }}</td>
                                    <td>&pound;@{{ account.sheet.totals.transIn }}</td>
                                    <td>&pound;@{{ account.sheet.totals.transOut }}</td>
                                    <td v-if="account.sheet.totalsMatch" class="table-success">
                                        &pound;@{{ account.sheet.totals.totDown }}
                                    </td>
                                    <td v-else class="table-danger">
                                        A: &pound;@{{ account.sheet.totals.totAcross }}<br>
                                        D: &pound;@{{ account.sheet.totals.totDown }}
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

                    <div class="card-body p-0" style="height: 500px; overflow-y: scroll;">
                        <table class="table table-striped mb-0">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Transaction</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="transaction in transactions">
                                    <td>@{{ transaction.label }}</td>
                                    <td>&pound;@{{ transaction.amount }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</view-old-sheet>
@endsection
