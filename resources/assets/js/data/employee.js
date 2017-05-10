Vue.component('spark-data-employee-screen', $.extend(true, {
    /*
     * Bootstrap the component. Load the initial data.
     */
    ready: function () {
        this.getVueItems(this.pagination.current_page);
    },


    /*
     * Initial state of the component's data.
     */
    data: function () {
        return {
            items: [],
            pagination: {
                total: 0,
                per_page: 2,
                from: 1,
                to: 0,
                last_page: 1,
                current_page: 1
            },
            offset: 2,
            formErrors:{},
            formErrorsUpdate:{},
            columns : [
                { 'name': 'id',  'displayName': 'id',
                    'formInputType': 'text',
                    'formInputPlaceholder': '',
                    'newItemModel': '',
                    'fillItemModel': ''
                },
                { 'name': 'nome', 'displayName': 'nome',
                    'formInputType': 'text',
                    'formInputPlaceholder': '',
                    'newItemModel': '',
                    'fillItemModel': ''
                },
                { 'name': 'data_nascimento', 'displayName': 'data_nascimento',
                    'formInputType': 'text',
                    'formInputPlaceholder': '',
                    'newItemModel': '',
                    'fillItemModel': ''
                },
                { 'name': 'observacao', 'displayName': 'observacao',
                    'formInputType': 'text',
                    'formInputPlaceholder': '',
                    'newItemModel': '',
                    'fillItemModel': ''
                },
            ]
        };
    },


    events: {
        /*
         * Handle the "userRetrieved" event.
         */
        userRetrieved: function (user) {
            this.user = user;

            this.updateProfileBasicsFormForNewUser(user);

            return true;
        }
    },


    methods: {
        getVueItems: function(page) {
            this.$http.get('/erpnet-api/partner?page='+page).then((response) => {
                if (response.data.hasOwnProperty('data')) this.$set('items', response.data.data);
                if (response.data.hasOwnProperty('pagination')) this.$set('pagination', response.data.pagination);
            });
        },
        editItem: function(item) {
            this.columns.forEach(column=>{
                column.fillItemModel = item[column.name];
            });
            $("#edit-item").modal('show');
        },
        deleteItem: function(item) {
            this.$http.delete('/erpnet-api/partner/'+item.id)
                .then(
                    response => {
                        this.changePage(this.pagination.current_page);
                        toastr.success('Post Deleted Successfully.', 'Success Alert');
                    },
                    response => {
                        toastr.warning('Post Deleted Failed.', 'Fail Alert');
                    }
                );
        },
        changePage: function(page) {
            this.pagination.current_page = page;
            this.getVueItems(page);
        },
        /**
         * Update the user profile form with new user information.
         */
        updateProfileBasicsFormForNewUser: function (user) {
            this.forms.updateProfileBasics.name = user.name;
            this.forms.updateProfileBasics.email = user.email;
        },


        /**
         * Update the user's profile information.
         */
        updateProfileBasics: function () {
            var self = this;

            Spark.put('/settings/user', this.forms.updateProfileBasics)
                .then(function () {
                    self.$dispatch('updateUser');
                });
        }
    },


    computed: {
        isActived: function() {
            return this.pagination.current_page;
        },
        pagesNumber: function() {
            if (!this.pagination.last_page>1) {
                return [];
            }

            let pagesArray = [];
            for (let page = parseInt(this.pagination.current_page)-parseInt(this.offset);
                 page <= parseInt(this.pagination.current_page)+parseInt(this.offset); page++) {
                if (page>0 && page<=this.pagination.last_page) pagesArray.push(page);
            }
            return pagesArray;
        }
    },
}, Spark.components.employee));
