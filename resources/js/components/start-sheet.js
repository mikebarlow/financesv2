Vue.component('start-sheet', {
    props: ['accountid'],
    mixins: [
        require('./../mixins/alerts'),
        require('./../mixins/processing')
    ],
    data: function () {
        return {
            account: {},
            sheet: {
                start: ''
            },
            rows: {},
            bfRows: {},
            budgettotal: 0,
            bftotal: 0
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
                dateFormat: 'dd/mm/yy',
                firstDay: 1,
                onClose: function (dateText, obj) {
                    parent.$set(parent.sheet, 'start', dateText);
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

                        } else {
                            parent.dangerAlert('There was a problem loading the account');
                        }
                    },
                    (error) => {
                        parent.dangerAlert('Something went wrong when attempting to load the account');
                    }
                );
        },
    }
});
