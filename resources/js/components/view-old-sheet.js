Vue.component('view-old-sheet', {
    props: ['accountid', 'sheetid'],
    mixins: [
        require('./../mixins/alerts'),
        require('./../mixins/processing')
    ],
    data: function () {
        return {
            account: {
                sheet: {
                    rows: [],
                    totals: {}
                }
            },
        }
    },
    created: function() {
        var parent = this;
        this.getAccount();
    },

    methods: {
        getAccount: function () {
            var parent = this;

            axios.get(
                route('api.accounts.get.old', {id: this.accountid, sheetId: this.sheetid})
            )
            .then(
                (response) => {
                    if (response.status == 200) {
                        this.account = response.data.account;
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
