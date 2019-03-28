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
            }
        }
    },

    methods: {
        addRow: function () {
            this.budget.rows.push(this.newRow);
            this.newRow = {
                name: '',
                amount: ''
            };
        }
    }
});
