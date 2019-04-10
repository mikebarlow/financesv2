Vue.component('end-sheet', {
    props: ['accountid'],
    mixins: [
        require('./../mixins/alerts'),
        require('./../mixins/processing')
    ],
    data: function () {
        return {
            account: {},
            end_date: '',
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

            axios.get(route('api.accounts.get', {id: this.accountid}))
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
        saveSheet: function (event) {
            this.processing($(event.target), true);
            var parent = this;

            var formData = {
                sheet: {
                    account_id: this.accountid,
                    id: this.account.latest.id,
                    end_date: this.end_date,
                }
            };

            axios.post(
                route('api.sheets.complete'),
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
