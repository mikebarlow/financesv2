Vue.component('start-sheet', {
    props: ['accountid'],
    mixins: [
        require('./../mixins/alerts'),
        require('./../mixins/processing')
    ],
    data: function () {
        return {
            account: {},
            start_date: '',
            rows: {},
            bfRows: {},
            budgettotal: 0,
            bftotal: 0,
            grandTotal: 0
        }
    },
    watch: {
        "rows": {
            handler: function(rows) {
                var total = 0;

                for (var key in rows) {
                    total += parseFloat(rows[key].amount.replace(/,/g, ''));
                }
                this.budgettotal = total;
                this.grandTotal = this.budgettotal + this.bftotal;
            },
            deep: true
        },
        "bfRows": {
            handler: function(bfRows) {
                var bftotal = 0;

                for (var key in bfRows) {
                    bftotal += parseFloat(bfRows[key].amount.replace(/,/g, ''));
                }
                this.bftotal = bftotal;
                this.grandTotal = this.budgettotal + this.bftotal;
            },
            deep: true
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
                    parent.$set(parent, 'start_date', dateText);
                }
            });
        });
    },

    methods: {
        getAccount: function () {
            var parent = this;

            axios.get(route('api.accounts.get', {id: this.accountid}))
                .then(
                    (response) => {
                        if (response.status == 200) {
                            this.account = response.data.account;
                            this.rows = JSON.parse(JSON.stringify(this.account.budget.rows));
                            this.bfRows = JSON.parse(JSON.stringify(this.account.budget.rows));

                            for (var key in this.bfRows) {
                                this.bfRows[key].amount = '0.00';
                            }

                            if (typeof this.account.latest == 'object') {
                                for (var key in this.account.latest.rows) {
                                    var row = this.account.latest.rows[key];

                                    this.bfRows[row.budget_id].amount = row.total;
                                }
                            }
                        } else {
                            parent.dangerAlert('There was a problem loading the account');
                        }
                    },
                    (error) => {
                        parent.dangerAlert('Something went wrong when attempting to load the account');
                    }
                );
        },

        saveSheet: function (event) {
            this.processing($(event.target), true);
            var parent = this;

            var formData = {
                sheet: {
                    account_id: this.accountid,
                    start_date: this.start_date,
                    budget: this.rows,
                    brought_forward: this.bfRows
                }
            };

            axios.post(
                route('api.sheets.create'),
                JSON.parse(JSON.stringify(formData))
            )
            .then(
                (response) => {
                    if (response.status === 201) {
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
                    parent.dangerAlert('There was a problem creating the sheet.');
                }
            );
        }
    }
});
