Vue.component('edit-budget', {
    props: ['budgetid'],
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
            total: 0
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
    created: function() {
        this.getBudget();
    },

    methods: {
        getBudget: function () {
            var parent = this;

            axios.get(route('api.budgets.get', {id: this.budgetid}))
                .then(
                    (response) => {
                        if (response.status == 200) {
                            this.budget = response.data.budget;
                        } else {
                            parent.dangerAlert('There was a problem loading the budget');
                        }
                    },
                    (error) => {
                        parent.dangerAlert('Something went wrong when attempting to load the budget');
                    }
                );
        },
        addRow: function () {
            if (this.newRow.name != '' && this.newRow.amount > 0) {
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
                budget: this.budget
            };

            axios.post(
                route('api.budgets.update', {id: this.budgetid}),
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
                    parent.dangerAlert('There was a problem saving the budget, please make sure all fields are filled in');
                }
            );
        },
        deleteRow: function (key) {
            this.budget.rows.splice(key, 1);
        }
    }
});
