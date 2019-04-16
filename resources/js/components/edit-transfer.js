Vue.component('edit-transfer', {
    props: ['sheetid', 'transferid'],
    mixins: [
        require('./../mixins/alerts'),
        require('./../mixins/processing')
    ],
    data: function () {
        return {
            transfer: {
                name: '',
                rows: []
            },
            newRow: {
                from_id: 0,
                from_label: '',
                to_account: 0,
                to_account_lbl: '',
                to_row_select: [],
                to_row: 0,
                to_label: '',
                amount: 0.00
            },
            total: 0,
            rowCache: {}
        }
    },
    watch: {
        "transfer.rows": function(rows) {
            var total = 0;

            for (var key in rows) {
                total += parseFloat(rows[key].amount.replace(/,/g, ''));
            }
            this.total = total;
        },
        "newRow.to_account": function (sheetId) {
            if (sheetId != 'other') {
                if (typeof this.rowCache[sheetId] == 'undefined') {
                    this.getAccountRows(sheetId, 'to_row_select');
                } else {
                    this.newRow.to_row_select = this.rowCache[sheetId].rows;
                    this.newRow.to_account_lbl = this.rowCache[sheetId].accountName;
                }
            } else {
                this.newRow.to_row = 0;
                this.newRow.to_account_lbl = 'Other';
            }
        }
    },
    created: function() {
        var parent = this;
        this.getAccountRows(this.sheetid, '');
        this.getTransfer();
    },

    methods: {
        getTransfer: function () {
            var parent = this;

            axios.get(route('api.masstransfers.get', {id: this.transferid}))
                .then(
                    (response) => {
                        if (response.status == 200) {
                            this.transfer = response.data.transfer;
                        } else {
                            parent.dangerAlert('There was a problem loading the transfers');
                        }
                    },
                    (error) => {
                        console.log(error);

                        parent.dangerAlert('Something went wrong when attempting to load the transfers');
                    }
                );
        },

        getAccountRows: function (sheetId, rows) {
            var parent = this;

            if (sheetId == 0) {
                return;
            }

            axios.get(route('api.sheets.rows', {id: sheetId}))
                .then(
                    (response) => {
                        if (response.status == 200) {
                            if (rows.length > 0) {
                                parent.$set(parent.newRow, rows, response.data.rows);
                                parent.$set(parent.newRow, 'to_account_lbl', response.data.accountName);
                            }

                            parent.$set(parent.rowCache, sheetId, response.data);
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

        addRow: function () {
            if (this.newRow.from_id != 0 && (this.newRow.to_row != 0 || this.newRow.to_label != '')) {

                for (i in this.rowCache[this.sheetid].rows) {
                    if (this.newRow.from_id == this.rowCache[this.sheetid].rows[i].budget_id) {
                        this.newRow.from_label = this.rowCache[this.sheetid].rows[i].label;
                    }
                }

                for (i in this.newRow.to_row_select) {
                    if (this.newRow.to_row == this.newRow.to_row_select[i].budget_id) {
                        this.newRow.to_label = this.newRow.to_row_select[i].label;
                    }
                }

                this.transfer.rows.push(this.newRow);

                this.newRow = {
                    from_id: 0,
                    from_label: '',
                    to_account: 0,
                    to_row_select: [],
                    to_row: 0,
                    to_label: '',
                    amount: 0.00
                };
            } else {
                this.dangerAlert('Both label and amount are required');
            }
        },
        saveTransfer: function (event) {
            this.processing($(event.target), true);
            var parent = this;

            var formData = {
                sheetId: this.sheetid,
                transfer: this.transfer
            };

            axios.post(
                route('api.masstransfers.update', {id: this.transferid}),
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
                    parent.dangerAlert('There was a problem saving the mass transfer, please make sure all fields are filled in');
                }
            );
        },
        deleteRow: function (key) {
            this.transfer.rows.splice(key, 1);
        }
    }
});
