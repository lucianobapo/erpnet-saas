<div id="spark-data-internal-course">
    <spark-data-internal-course-screen inline-template>
        <div id="spark-data-internal-course-screen" class="panel panel-default">
            <div class="panel-heading">{{ t('Internal Course') }}</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-12 well well-sm">
                        <div class="pull-right">
                            <button  class="btn btn-primary" @click.prevent="newItem">
                                <span class="glyphicon glyphicon-file"></span> {{ t('New') }}
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row" v-if="pagination.total==0">
                    <div class="col-xs-12 well well-sm text-center">
                        <em>{{ t('No records found') }}</em>
                    </div>
                </div>

                @include('erpnetSaas::data.tabs.parts.nav')

                <div class="row" v-if="pagination.total>0">
                    <div v-for="item in items" class="col-xs-12 well well-sm">
                        <div class="row">
                            <div class="col-xs-12 col-sm-8 col-md-6">
                                <ul>
                                    <li v-for="column in columns">
                                        @{{ getTrans(column.displayName) }}: @{{ item[column.name] }}
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

                @include('erpnetSaas::data.tabs.parts.nav')

            </div>

        </div>
    </spark-data-internal-course-screen>

</div>
