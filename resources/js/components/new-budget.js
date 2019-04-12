Vue.component('new-budget', {
    mixins: [
        require('./../mixins/alerts'),
        require('./../mixins/processing')
    ],
    data: function () {
        return {
            budget: {
                name: '',
                rows: []
            },
            newRow: {
                name: '',
                amount: ''
            },
            total: 0,
            share: false
        }
    },
    watch: {
        "budget.rows": function(rows) {
            var total = 0;

            for (var key in rows) {
                total += parseFloat(rows[key].amount.replace(/,/g, ''));
            }
            this.total = total;
        }
    },

    methods: {
        addRow: function () {
            if (this.newRow.name != '') {
                this.budget.rows.push(this.newRow);

                this.newRow = {
                    name: '',
                    amount: ''
                };
            } else {
                this.dangerAlert('Both label and amount are required');
            }
        },
        saveBudget: function (event) {
            this.processing($(event.target), true);
            var parent = this;

            var formData = {
                budget: this.budget,
                share: this.share
            };

            axios.post(
                route('api.budgets.create'),
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
                    parent.dangerAlert('There was a problem saving the budget, please make sure all fields are filled in');
                }
            );
        },
        deleteRow: function (key) {
            this.budget.rows.splice(key, 1);
        }
    }
});
