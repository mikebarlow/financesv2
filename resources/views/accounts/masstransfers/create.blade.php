@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col">
            <new-transfer :sheetid="{{ $account->latestSheet->id }}" inline-template>
                <div class="card">
                    <div class="card-header">
                        <div class="pull-left d-inline-block">Create New Mass Transfer for {{ $account->name }}</div>

                        <button class="btn-card btn-card-right btn-success float-right" @click.prevent="saveTransfer">
                            Save
                        </button>
                    </div>

                    <div class="card-body">
                        @include('alerts.main')

                        <form>
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="transfer-name" class="form-control" v-model="transfer.name">
                            </div>

                            <table class="mt-4 table table-striped">
                                <thead>
                                    <tr>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Amount</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody v-if="transfer.rows.length > 0">
                                    <tr v-for="(row, key) in transfer.rows">
                                        <td>@{{ row.from_label }}</td>
                                        <td>
                                            @{{ row.to_account_lbl }}::@{{ row.to_label }}
                                        </td>
                                        <td>&pound;@{{ row.amount | currency(2) }}</td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm" @click.prevent="deleteRow(key)">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Total</th>
                                        <td colspan="2">&pound;@{{ total | currency(2) }}</td>
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                    <tr>
                                        <td colspan="4">
                                            Add rows to your Mass Transfer
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="form-row">
                                <div class="form-group col-6">
                                    <select name="from_id" class="form-control" v-model="newRow.from_id">
                                        <option value="0">Select Row</option>
                                        @foreach ($budgetRows as $row)
                                            <option value="{{ $row['id'] }}">
                                                {{ $row['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-6">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">&pound;</span>
                                        </div>
                                        <input type="number" name="amount" class="form-control" v-model="newRow.amount" placeholder="100.50">
                                    </div>
                                </div>

                                <div class="form-group col-6">
                                    <select class="form-control" v-model="newRow.to_account">
                                        <option value="0">Select Account To</option>
                                        @foreach ($allAccounts as $account)
                                            @if ($account->latestSheet === null)
                                                @continue
                                            @endif
                                            <option value="{{ $account->latestSheet->id }}">
                                                {{ $account->name}}
                                            </option>
                                        @endforeach
                                        <option value="other">Other</option>
                                    </select>
                                </div>

                                <div class="form-group col-4">
                                    <input type="text" class="form-control" placeholder="Label (e.g. Savings)" v-model="newRow.to_label" v-if="newRow.to_account == 'other'">
                                    <select class="form-control" v-model="newRow.to_row" v-else>
                                        <option value="0">Select Row To</option>
                                        <option v-for="row in newRow.to_row_select" :value="row.budget_id">
                                            @{{ row.label }}
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group col-2">
                                    <button type="button" class="btn btn-primary" @click.prevent="addRow">Add Row</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </new-transfer>
        </div>
    </div>
</div>
@endsection
