@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col">
            <new-budget inline-template>
                <div class="card">
                    <div class="card-header">
                        <div class="pull-left d-inline-block">Create New Budget</div>

                        <button class="btn-card btn-card-right btn-success float-right" @click.prevent="saveBudget">
                            Save
                        </button>
                    </div>

                    <div class="card-body">
                        @include('alerts.main')

                        <form>
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="budget-name" class="form-control" v-model="budget.name">
                            </div>

                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Label</th>
                                        <th>Amount</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody v-if="budget.rows.length > 0">
                                    <tr v-for="(row, key) in budget.rows">
                                        <td>@{{ row.name }}</td>
                                        <td>&pound;@{{ row.amount | currency(2) }}</td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm" @click.prevent="deleteRow(key)">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </td>
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

                            <div class="form-row">
                                <div class="form-group col-5">
                                    <input type="text" name="name" class="form-control" v-model="newRow.name" placeholder="Label (e.g. Mortgage)">
                                </div>
                                <div class="form-group col-5">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">&pound;</span>
                                        </div>
                                        <input type="number" name="amount" class="form-control" v-model="newRow.amount" placeholder="100.50">
                                    </div>
                                </div>
                                <div class="form-group col-2">
                                    <button type="button" class="btn btn-primary" @click.prevent="addRow">Add Row</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </new-budget>
        </div>
    </div>
</div>
@endsection
