<div id="spark-data-employee">
    <spark-data-employee-screen inline-template>
        <div id="spark-data-employee-screen" class="panel panel-default">
            <div class="panel-heading">{{ t('Employee') }}</div>
            <div class="panel-body" id="employeeData">
                <div class="row">
                    <div class="col-xs-12 well well-sm">
                        <div class="pull-right">
                            <button type="button" data-toggle="modal" data-target="#create-item"
                                    class="btn btn-primary">
                                <span class="glyphicon glyphicon-file"></span> {{ t('New') }}
                            </button>
                        </div>
                    </div>
                </div>

                @include('erpnetSaas::data.tabs.employee.nav')

                <div class="row">
                    <div v-for="item in items" class="col-xs-12 well well-sm">
                        <div class="row">
                            <div class="col-xs-12 col-sm-8 col-md-6">
                                <ul>
                                    <li v-for="column in columns">
                                        @{{ column.displayName }}: @{{ item[column.name] }}
                                    </li>
                                </ul>
                            </div>

                            <div class="col-xs-12 col-sm-4 col-md-6">
                                <div class="pull-right">
                                    <button  class="edit-modal btn btn-warning" @click.prevent="editItem(item)">
                                        <span class="glyphicon glyphicon-edit"></span> {{ t('Edit') }}
                                    </button>
                                    <button  class="edit-modal btn btn-danger" @click.prevent="deleteItem(item)">
                                        <span class="glyphicon glyphicon-trash"></span> {{ t('Remove') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @include('erpnetSaas::data.tabs.employee.nav')
                @include('erpnetSaas::data.tabs.employee.create')
                @include('erpnetSaas::data.tabs.employee.edit')
            </div>

        </div>
    </spark-data-employee-screen>

</div>
