Vue.component('view-sheet', {
    props: ['accountid'],
    mixins: [
        require('./../mixins/alerts'),
        require('./../mixins/processing')
    ],
    data: function () {
        return {
            end_date: '',
            account: {
                latest: {
                    rows: [],
                    totals: {}
                }
            },
            transactions: [],
            defaults: {
                payment: {
                    row: 0,
                    amount: 0.00
                },
                transfer: {
                    from_account: 0,
                    from_row_select: [],
                    from_row: 0,
                    from_label: '',
                    to_account: 0,
                    to_row_select: [],
                    to_row: 0,
                    to_label: '',
                    amount: 0.00
                }
            },
            payment: {
                row: 0,
                amount: 0.00
            },
            transfer: {
                from_account: 0,
                from_row_select: [],
                from_row: 0,
                from_label: '',
                to_account: 0,
                to_row_select: [],
                to_row: 0,
                to_label: '',
                amount: 0.00
            },
            rowCache: {}
        }
    },
    watch: {
        "transfer.from_account": function (sheetId) {
            if (sheetId != 'other') {
                if (typeof this.rowCache[sheetId] == 'undefined') {
                    this.getAccountRows(sheetId, 'from_row_select');
                } else {
                    this.transfer.from_row_select = this.rowCache[sheetId];
                }
            } else {
                this.transfer.from_row = 0;
            }
        },
        "transfer.to_account": function (sheetId) {
            if (sheetId != 'other') {
                if (typeof this.rowCache[sheetId] == 'undefined') {
                    this.getAccountRows(sheetId, 'to_row_select');
                } else {
                    this.transfer.to_row_select = this.rowCache[sheetId];
                }
            } else {
                this.transfer.to_row = 0;
            }
        }
    },
    created: function() {
        var parent = this;
        this.getAccount();

        $(document).ready(function() {
            $('.datepicker').datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: 'yy-mm-dd',
                firstDay: 1,
                onClose: function (dateText, obj) {
                    parent.$set(parent, 'end_date', dateText);
                }
            });
        });
    },

    methods: {
        getAccount: function () {
            var parent = this;

            axios.get(route('api.accounts.get.latest', {id: this.accountid}))
                .then(
                    (response) => {
                        if (response.status == 200) {
                            this.account = response.data.account;
                            this.getTransactions();
                        } else {
                            parent.dangerAlert('There was a problem loading the account');
                        }
                    },
                    (error) => {
                        parent.dangerAlert('Something went wrong when attempting to load the account');
                    }
                );
        },
        getAccountRows: function (sheetId, rows) {
            var parent = this;

            axios.get(route('api.sheets.rows', {id: sheetId}))
                .then(
                    (response) => {
                        if (response.status == 200) {
                            parent.$set(parent.transfer, rows, response.data.rows);
                            parent.$set(parent.rowCache, sheetId, response.data.rows);
                        } else {
                            parent.dangerAlert('There was a problem loading the account');
                            return [];
                        }
                    },
                    (error) => {
                        parent.dangerAlert('Something went wrong when attempting to load the account');
                        return [];
                    }
                );
        },
        getTransactions: function () {
            var parent = this;

            axios.get(route('api.sheets.transactions', {id: this.account.latest.id}))
                .then(
                    (response) => {
                        if (response.status == 200) {
                            this.transactions = response.data.transactions;
                        } else {
                            parent.dangerAlert('There was a problem loading the transactions');
                        }
                    },
                    (error) => {
                        parent.dangerAlert('Something went wrong when attempting to load the transactions');
                    }
                );
        },
        deleteTransaction: function (event, id) {
            if (confirm('Are you sure?')) {
                var parent = this;

                axios.delete(
                    route('api.transaction.delete', {id: id})
                )
                .then(
                    (response) => {
                        if (response.status === 200) {
                            this.getAccount();
                        } else {
                            parent.dangerAlert(response.data.error[0]);
                        }
                    },
                    (error) => {
                        parent.dangerAlert('There was a problem logging the payment.');
                    }
                );
            }
        },
        sendPayment: function (event) {
            this.processing($(event.target), true);
            var parent = this;

            var formData = {
                sheet_id: this.account.latest.id,
                payment: this.payment
            };

            axios.post(
                route('api.sheets.payment'),
                JSON.parse(JSON.stringify(formData))
            )
            .then(
                (response) => {
                    if (response.status === 201) {
                        this.payment = JSON.parse(JSON.stringify(this.defaults.payment));
                        this.getAccount();
                        this.stopProcessing($(event.target));
                    } else {
                        this.stopProcessing($(event.target));
                        parent.dangerAlert(response.data.error[0]);
                    }
                },
                (error) => {
                    this.stopProcessing($(event.target));
                    parent.dangerAlert('There was a problem logging the payment.');
                }
            );
        },
        sendTransfer: function (event) {
            this.processing($(event.target), true);
            var parent = this;

            var formData = {
                sheet_id: this.account.latest.id,
                transfer: {
                    fromSheet: this.transfer.from_account,
                    from: this.transfer.from_row,
                    fromLabel: this.transfer.from_label,
                    toSheet: this.transfer.to_account,
                    to: this.transfer.to_row,
                    toLabel: this.transfer.to_label,
                    amount: this.transfer.amount
                }
            };

            axios.post(
                route('api.sheets.transfer'),
                JSON.parse(JSON.stringify(formData))
            )
            .then(
                (response) => {
                    if (response.status === 201) {
                        this.transfer = JSON.parse(JSON.stringify(this.defaults.transfer));
                        this.getAccount();
                        this.stopProcessing($(event.target));
                    } else {
                        this.stopProcessing($(event.target));
                        parent.dangerAlert(response.data.error[0]);
                    }
                },
                (error) => {
                    this.stopProcessing($(event.target));
                    parent.dangerAlert('There was a problem logging the transfer.');
                }
            );
        },
        completeSheet: function (event) {
            this.processing($(event.target), true);
            var parent = this;

            var formData = {
                sheet_id: this.account.latest.id,
                end_date: this.end_date
            };

            axios.post(
                route('api.sheets.complete', {id: this.account.id}),
                JSON.parse(JSON.stringify(formData))
            )
            .then(
                (response) => {
                    if (response.status === 200) {
                        parent.successAlert(response.data.msg);

                        setTimeout(function () {
                            window.location = response.data.redirect
                        }, 2000);
                    } else {
                        this.stopProcessing($(event.target));
                        parent.dangerAlert(response.data.error[0]);
                    }
                },
                (error) => {
                    this.stopProcessing($(event.target));
                    parent.dangerAlert('There was a problem completing the sheet.');
                }
            );
        }
    }
});
