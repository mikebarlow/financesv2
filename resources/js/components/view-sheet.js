Vue.component('view-sheet', {
    props: ['accountid'],
    mixins: [
        require('./../mixins/alerts'),
        require('./../mixins/processing')
    ],
    data: function () {
        return {
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
                }
            },
            payment: {
                row: 0,
                amount: 0.00
            }
        }
    },
    created: function() {
        var parent = this;
        this.getAccount();
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
        }
    }
});
