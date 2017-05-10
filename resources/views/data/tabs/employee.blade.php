<div id="spark-data-employee">
    <spark-data-employee-screen inline-template>
        <div id="spark-data-employee-screen" class="panel panel-default">
            <div class="panel-heading">{{ t('Employee') }}</div>
            <div class="panel-body" id="employeeData">
                {{--<h2>Lista de usuários:</h2>--}}
                <div class="row">
                    <div class="col-md-12 well well-sm">
                        <div class="pull-right">
                            <button type="button" data-toggle="modal" data-target="#create-item"
                                    class="btn btn-primary">
                                <span class="glyphicon glyphicon-file"></span> {{ t('New') }}
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div v-for="item in items" class="col-md-12 well well-sm">
                        <div class="pull-right">
                            <button  class="edit-modal btn btn-warning" @click.prevent="editItem(item)">
                                <span class="glyphicon glyphicon-edit"></span> {{ t('Edit') }}
                            </button>
                            <button  class="edit-modal btn btn-danger" @click.prevent="deleteItem(item)">
                                <span class="glyphicon glyphicon-trash"></span> {{ t('Remove') }}
                            </button>
                        </div>
                        <ul>
                            <li v-for="column in columns">
                                @{{ column.displayName }}: @{{ item[column.name] }}
                            </li>
                        </ul>
                    </div>

                </div>

                <nav v-if="pagination.total>0">
                    <ul class="pagination">
                        <li v-if="pagination.current_page > 1">
                            <a href="#" aria-label="Previous" @click.prevent="changePage(pagination.current_page - 1)">
                                <span aria-hidden="true">«</span>
                            </a>
                        </li>
                        <li v-for="page in pagesNumber" v-bind:class="[ page == isActived ? 'active' : '']">
                            <a href="#" @click.prevent="changePage(page)">
                                @{{ page }}
                            </a>
                        </li>
                        <li v-if="pagination.current_page < pagination.last_page">
                            <a href="#" aria-label="Next" @click.prevent="changePage(pagination.current_page + 1)">
                                <span aria-hidden="true">»</span>
                            </a>
                        </li>
                    </ul>
                </nav>

                @include('erpnetSaas::data.tabs.employee.create')
                @include('erpnetSaas::data.tabs.employee.edit')
            </div>

        </div>
    </spark-data-employee-screen>

</div>
