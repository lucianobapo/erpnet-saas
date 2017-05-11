<!-- Create Item Modal -->
<div class="modal fade" id="create-item" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">{{ t('Create New Data') }}</h4>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" v-on:submit.prevent="createItem">

                    <div class="form-group" v-for="column in columns">
                        <label for="@{{ column.name }}">@{{ getTrans(column.displayName) }}</label>
                        <input name="@{{ column.name }}" class="form-control"
                               type="@{{ column.formInputType }}" placeholder="@{{ column.formInputPlaceholder }}"
                               v-model="newItem[column.name]" />
                        <span v-if="formErrors[column.name]" class="error text-danger">@{{ formErrors[column.name] }}</span>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-btn fa-save"></i> {{ t('Save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>