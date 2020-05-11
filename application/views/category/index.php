

            <!-- Main content -->
            <section class="content">
                <div class="card">
                    <div class="card-header">
                        <button type="button" data-toggle="modal" data-target="#modal-add" class="btn btn-primary" style="background-image: linear-gradient(to right bottom, #00C6FF, #0072FF)"><i class="fas fa-plus-square"></i> Tambah Kategori</button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                           <table id="category" class="table table-bordered table-striped nowrap" width="100%">
                              <thead>
                                 <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Nama Kategori</th>
                                    <th class="text-center">Deskipsi</th>
                                    <th class="text-center">Icon</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Actions</th>
                                 </tr>
                              </thead>
                              <tbody></tbody>
                              <tfoot>
                                 <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Nama Kategoro</th>
                                    <th class="text-center">Deskripsi</th>
                                    <th class="text-center">Icon</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Actions</th>
                                 </tr>
                              </tfoot>
                           </table>
                        </div>
                    </div>
                </div>
            </section>

            <div class="modal fade" id="modal-add">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary" style="background-image: linear-gradient(to right bottom, #00C6FF, #0072FF)">
                            <h4 class="modal-title">Tambah Kategori</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                        </div>
                        <form id="form-save-category" method="post">
                            <div class="modal-body">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="name">Nama Kategori</label>
                                            <input type="text" class="form-control" name="category_name" id="category_name" placeholder="Masukkan nama kategori">
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Deskripsi</label>
                                            <input type="text" class="form-control" name="category_description" id="category_description" placeholder="Masukkan deskripsi kategori">
                                        </div>
                                        <div class="form-group">
                                            <label for="icon">Icon</label>
                                            <input type="file" class="form-control" name="category_icon" id="category_icon" accept="image/png, image/jpeg, image/gif">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                <button type="submit" id="btn-save-category" class="btn btn-primary">Tambah</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->

            <div class="modal fade" id="modal-edit-category">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary" style="background-image: linear-gradient(to right bottom, #00C6FF, #0072FF)">
                            <h4 class="modal-title">Perbaharui Kategori</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                        </div>
                        <form id="form-edit-category" type="post">
                            <div class="modal-body">
                                <div class="card">
                                    <div class="card-body">
                                        <input type="hidden" name="category_id">
                                        <div class="form-group">
                                            <label for="name">Nama Kategori</label>
                                            <input type="text" class="form-control" name="category_name_edit" id="category_name_edit" placeholder="Category Name">
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Deskripsi</label>
                                            <input type="text" class="form-control" name="category_description_edit" id="category_description_edit" placeholder="Category Description">
                                        </div>
                                        <div class="form-group">
                                            <label for="icon">Icon (jika diperlukan)</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" name="category_icon_edit" id="category_icon_edit" accept="image/png, image/jpeg, image/gif">
                                                <label class="custom-file-label" for="customFile">Choose file</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-primary">Perbaharui</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
        