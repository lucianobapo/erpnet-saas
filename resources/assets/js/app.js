/*
 |--------------------------------------------------------------------------
 | Laravel Spark - Creating Amazing Experiences.
 |--------------------------------------------------------------------------
 |
 | First, we will load all of the "core" dependencies for Spark which are
 | libraries such as Vue and jQuery. This also loads the Spark helpers
 | for things such as HTTP calls, forms, and form validation errors.
 |
 | Next, we will create the root Vue application for Spark. This'll start
 | the entire application and attach it to the DOM. Of course, you may
 | customize this script as you desire and load your own components.
 |
 */


require('./core/bootstrap');

new Vue(require('./spark'));

const employeeData = new Vue({
    el :'#employeeData',
    data : {
        items: [],
        pagination: {
            total: 0,
            per_page: 2,
            from: 1,
            to: 0,
            current_page: 1
        },
        offset: 4,
        formErrors:{},
        formErrorsUpdate:{},
        columns : [
            { 'name': 'id', 'displayName': 'id'},
            { 'name': 'nome', 'displayName': 'nome'},
            { 'name': 'data_nascimento', 'displayName': 'data_nascimento'},
            { 'name': 'observacao', 'displayName': 'observacao'},
        ],
        newItem : {
            'title':'',
            'description':'',
            'username':'',
            'email':''
        },
        fillItem : {
            'title':'',
            'description':'',
            'username':'',
            'email':'',
            'id':''
        }
    },
    computed: {
        isActived: function() {
            return this.pagination.current_page;
        },
        pagesNumber: function() {
            if (!this.pagination.to) {
                return [];
            }
            var from = this.pagination.current_page - this.offset;
            if (from < 1) {
                from = 1;
            }
            var to = from + (this.offset * 2);
            if (to >= this.pagination.last_page) {
                to = this.pagination.last_page;
            }
            var pagesArray = [];
            while (from <= to) {
                pagesArray.push(from);
                from++;
            }
            return pagesArray;
        }
    },
    ready: function() {
        this.getVueItems(this.pagination.current_page);
    },
    methods: {
        sendMessage: function(item) {
            this.$http.delete('/vueitems/'+item.id).then((response) => {
                this.changePage(this.pagination.current_page);
                toastr.success('Post Deleted Successfully.', 'Success Alert', {timeOut: 5000});
            });
        },
        getVueItems: function(page) {
            this.$http.get('/erpnet-api/partner?page='+page).then((response) => {
                if (response.data.hasOwnProperty('data')) this.$set('items', response.data.data.data);
                if (response.data.hasOwnProperty('pagination')) this.$set('pagination', response.data.pagination);
            });
        },
        createItem: function() {
            var input = this.newItem;
            console.log(input);
            this.$http.post('/vueitems',input).then((response) => {
                this.changePage(this.pagination.current_page);
                this.newItem = {
                    'title':'',
                    'description':'',
                    'username':'',
                    'email':''
                };
                $("#create-item").modal('hide');
                toastr.success('Post Created Successfully.', 'Success Alert', {timeOut: 5000});
            }, (response) => {
                this.formErrors = response.data;
            });
        },
        deleteItem: function(item) {
            this.$http.delete('/vueitems/'+item.id).then((response) => {
                this.changePage(this.pagination.current_page);
                toastr.success('Post Deleted Successfully.', 'Success Alert', {timeOut: 5000});
            });
        },
        editItem: function(item) {
            this.fillItem.title = item.title;
            this.fillItem.id = item.id;
            this.fillItem.description = item.description;
            $("#edit-item").modal('show');
        },
        updateItem: function(id) {
            var input = this.fillItem;
            this.$http.put('/vueitems/'+id,input).then((response) => {
                this.changePage(this.pagination.current_page);
                this.newItem = {
                    'title':'',
                    'description':'',
                    'username':'',
                    'email':'',
                    'id':''
                };
                $("#edit-item").modal('hide');
                toastr.success('Item Updated Successfully.', 'Success Alert', {timeOut: 5000});
            }, (response) => {
                this.formErrors = response.data;
            });
        },
        changePage: function(page) {
            this.pagination.current_page = page;
            this.getVueItems(page);
        }
    }
});