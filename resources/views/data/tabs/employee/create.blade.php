<!-- Create Item Modal -->
<div class="modal fade" id="create-item" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Create New Post</h4>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" v-on:submit.prevent="createItem">

                    <div class="form-group">
                        <label for="from">Mensagem de:</label>
                        <input type="text" name="from" class="form-control" value="Tigresa VIP" v-model="newItem.from" />
                        <span v-if="formErrors['from']" class="error text-danger">
                                                @{{ formErrors['from'] }}
                                            </span>
                    </div>
                    <div class="form-group">
                        <label for="to">Para:</label>
                        <input type="text" name="to" class="form-control" value="luciano.bapo@gmail.com" v-model="newItem.to" />
                        <span v-if="formErrors['to']" class="error text-danger">
                                                @{{ formErrors['to'] }}
                                            </span>
                    </div>
                    <div class="form-group">
                        <label for="title">Assunto da Mensagem:</label>
                        <input type="text" name="title" class="form-control" value="Mensagem da Tigresa" v-model="newItem.title" />
                        <span v-if="formErrors['title']" class="error text-danger">
                                                @{{ formErrors['title'] }}
                                            </span>
                    </div>
                    <div class="form-group">
                        <label for="greeting">Introdução:</label>
                        <input type="text" name="greeting" class="form-control" value="Olá," v-model="newItem.greeting" />
                        <span v-if="formErrors['greeting']" class="error text-danger">
                                                @{{ formErrors['greeting'] }}
                                            </span>
                    </div>
                    <div class="form-group">
                        <label for="description">Mensagem:</label>
                        <textarea name="description" class="form-control" v-model="newItem.description">
                                                Hoje tenho novidades para todos!
                                            </textarea>
                        <span v-if="formErrors['description']" class="error text-danger">
                                                @{{ formErrors['description'] }}
                                            </span>
                    </div>
                    <div class="form-group">
                        <label for="salutation">Saudação:</label>
                        <input type="text" name="salutation" class="form-control" value="Até a próxima pessoal, Tigresa" v-model="newItem.salutation" />
                        <span v-if="formErrors['salutation']" class="error text-danger">
                                                @{{ formErrors['salutation'] }}
                                            </span>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success">Enviar Mensagem</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>