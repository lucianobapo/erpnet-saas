Vue.component('spark-data-employee-screen', $.extend(true, {
    /*
     * Bootstrap the component. Load the initial data.
     */
    ready: function () {
        this.getConfig();
    },


    /*
     * Initial state of the component's data.
     */
    data: function () {
        return {
            trans: [],
            config: [],
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
            columns : []
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
        getTrans: function(field) {
            if (field){
                if (this.trans.hasOwnProperty(field)) return this.trans[field];
                return field;
            }
            else
                this.$http.get('/erpnet-api/lang/'+this.config.defaultLocale+'/erpnetSaas')
                    .then(
                        response => {
                            if (response.data.hasOwnProperty('data'))
                                this.$set('trans', response.data.data);
                        },
                        response => {
                            if(toastr) toastr.warning('Retrieving trans Failed.', 'Fail Alert');
                        }
                    );
        },
        getConfig: function(field) {
            if (field){
                if (this.config.hasOwnProperty(field)) return this.config[field];
                else if(toastr) toastr.warning('Retrieving config Failed.', 'Fail Alert');
            }
            else
                this.$http.get('/erpnet-api/config/erpnetSaas')
                    .then(
                        response => {
                            if (response.data.hasOwnProperty('data'))
                                this.$set('config', response.data.data);
                            this.columns = this.config.employeeColumns;
                            this.getTrans();
                            this.getVueItems(this.pagination.current_page);
                            // console.log(this.items);
                        },
                        response => {
                            if(toastr) toastr.warning('Retrieving data Failed.', 'Fail Alert');
                        }
                    );
        },
        getVueItems: function(page) {
            let query = '';
            if (page>1) query = '?page='+page;
            this.$http.get(this.config.employeeApiUrl+query)
                .then(
                    response => {
                        if (response.data.hasOwnProperty('data')) this.$set('items', response.data.data);
                        if (response.data.hasOwnProperty('pagination')) this.$set('pagination', response.data.pagination);
                    },
                    response => {
                        if(toastr) toastr.warning('Retrieving data Failed.', 'Fail Alert');
                    }
                );
        },
        createItem: function() {
            let input = {};
            this.columns.forEach(column=>{
                if (column.name!='id')
                    input[column.name] =  column.fillItemModel;
            });
            input.mandante = this.getConfig('defaultMandante');
            // console.log(input);
            this.$http.post(this.getConfig('employeeApiUrl'),input)
                .then(
                    response => {
                        this.getVueItems(this.pagination.current_page);
                        this.changePage(this.pagination.current_page);
                        this.newItem = {};
                        $("#create-item").modal('hide');
                        if(toastr) toastr.success(response.data.message, 'Success Alert');
                    },
                    response => {
                        if(toastr) toastr.warning('Post data Failed.', 'Fail Alert');
                        this.formErrors = response.data;
                    });
        },
        newItem: function(item) {
            this.columns.forEach(column=>{
                column.fillItemModel = '';
            });
            $("#create-item").modal('show');
        },
        editItem: function(item) {
            this.columns.forEach(column=>{
                column.fillItemModel = item[column.name];
            });
            $("#edit-item").modal('show');
        },
        updateItem: function() {
            let input = {};
            this.columns.forEach(column=>{
                input[column.name] =  column.fillItemModel;
            });
            this.$http.put(this.getConfig('employeeApiUrl')+'/'+input.id,input)
                .then(
                    response => {
                        this.changePage(this.pagination.current_page);
                        $("#edit-item").modal('hide');
                        if(toastr) toastr.success(response.data.message, 'Success Alert');
                    },
                    response => {
                        if(toastr) toastr.warning('Put data Failed.', 'Fail Alert');
                        this.formErrors = response.data;
                    });
        },
        deleteItem: function(item) {
            this.$http.delete(this.config.employeeApiUrl+'/'+item.id)
                .then(
                    response => {
                        this.changePage(this.pagination.current_page);
                        if(toastr) toastr.success(response.data.message, 'Success Alert');
                    },
                    response => {
                        if(toastr) toastr.warning('Delete data Failed.', 'Fail Alert');
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
