<div class="col-6">
    <div class="card">
        <div class="card-header">
            <div class="pull-left d-inline-block">Transfer</div>

            <button type="submit" class="btn-card btn-card-right btn-primary float-right" @click.prevent="sendTransfer">
                Transfer
            </button>
        </div>

        <div class="card-body">
            <form>
                <div class="form-row">
                    <div class="form-group col-6">
                        <select class="form-control" v-model="transfer.from_account">
                            <option value="0">Select Account From</option>
                            @foreach ($allAccounts as $account)
                                @if ($account->latestSheet === null)
                                    @continue
                                @endif

                                <option value="{{ $account->latestSheet->id }}">{{ $account->name}}</option>
                            @endforeach
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="form-group col-6">
                        <input type="text" class="form-control" placeholder="Label (e.g. Refund)" v-model="transfer.from_label" v-if="transfer.from_account == 'other'">
                        <select class="form-control" v-model="transfer.from_row" v-else>
                            <option value="0">Select Row From</option>
                            <option v-for="row in transfer.from_row_select" :value="row.id">@{{ row.label }}</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-6">
                        <select class="form-control" v-model="transfer.to_account">
                            <option value="0">Select Account To</option>
                            @foreach ($allAccounts as $account)
                                @if ($account->latestSheet === null)
                                    @continue
                                @endif

                                <option value="{{ $account->latestSheet->id }}">{{ $account->name}}</option>
                            @endforeach
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="form-group col-6">
                        <input type="text" class="form-control" placeholder="Label (e.g. Savings)" v-model="transfer.to_label" v-if="transfer.to_account == 'other'">
                        <select class="form-control" v-model="transfer.to_row" v-else>
                            <option value="0">Select Row To</option>
                            <option v-for="row in transfer.to_row_select" :value="row.id">@{{ row.label }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">&pound;</span>
                        </div>
                        <input type="number" name="amount" class="form-control" placeholder="100.50" v-model="transfer.amount">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>