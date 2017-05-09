<!-- Edit Item Modal -->
<div class="modal fade" id="edit-item" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Edit Blog Post</h4>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" v-on:submit.prevent="updateItem(fillItem.id)">
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" name="title" class="form-control" v-model="fillItem.username" />
                        <span v-if="formErrorsUpdate['title']" class="error text-danger">
                                                @{{ formErrorsUpdate['title'] }}
                                            </span>
                    </div>
                    <div class="form-group">
                        <label for="title">Description:</label>
                        <textarea name="description" class="form-control" v-model="fillItem.email">
                                            </textarea>
                        <span v-if="formErrorsUpdate['description']" class="error text-danger">
                                                @{{ formErrorsUpdate['description'] }}
                                            </span>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>