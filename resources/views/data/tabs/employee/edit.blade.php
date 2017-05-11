<!-- Edit Item Modal -->
<div class="modal fade" id="edit-item" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">{{ t('Edit Data') }}</h4>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" v-on:submit.prevent="updateItem(fillItem.id)">
                    <div class="form-group" v-for="column in columns">
                        <label for="@{{ column.name }}">@{{ getTrans(column.displayName) }}</label>
                        <input name="@{{ column.name }}" class="form-control"
                               type="@{{ column.formInputType }}" placeholder="@{{ column.formInputPlaceholder }}"
                               v-model="column.fillItemModel" />
                        <span v-if="formErrorsUpdate[column.name]" class="error text-danger">@{{ formErrorsUpdate[column.name] }}</span>
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