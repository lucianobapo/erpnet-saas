<div id="spark-settings-profile-screen">
    <spark-settings-profile-basics-screen inline-template>
        <div id="spark-settings-profile-basics-screen" class="panel panel-default">
            <div class="panel-heading">{{ t('Employee') }}</div>
            <div class="panel-body" id="employeeData">
                {{--<h2>Lista de usuários:</h2>--}}
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" data-toggle="modal" data-target="#create-item"
                                class="btn btn-primary">
                            <span class="glyphicon glyphicon-file"></span> {{ t('New') }}
                        </button>
                    </div>
                    <div v-for="item in items" class="col-md-12">
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

                <nav>
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
    </spark-settings-profile-basics-screen>

</div>
