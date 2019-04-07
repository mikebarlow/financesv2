<div class="col-4">
    <div class="card">
        <div class="card-header">
            <div class="pull-left d-inline-block">Payment</div>

            <button type="submit" class="btn-card btn-card-right btn-success float-right" @click.prevent="sendPayment">
                Pay
            </button>
        </div>

        <div class="card-body">
            <form>
                <div class="form-group">
                    <select v-model="payment.row" class="form-control">
                        <option value="0">Select Row</option>
                        <option v-for="row in account.latest.rows" :value="row.id">
                            @{{ row.label }}
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">&pound;</span>
                        </div>
                        <input type="number" name="amount" class="form-control" v-model="payment.amount" placeholder="100.50">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>