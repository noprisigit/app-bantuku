

            <!-- Main content -->
            <section class="content">

                <div class="card">
                    <div class="card-header">
                        <button type="button" data-toggle="modal" data-target="#modal-add-slider" class="btn btn-primary" style="background-image: linear-gradient(to right bottom, #00C6FF, #0072FF)"><i class="fas fa-plus-square"></i> Tambah Slider</button>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="slider" class="table table-bordered table-striped nowrap" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Nama Slider</th>
                                        <th class="text-center">Deskripsi</th>
                                        <th class="text-center">Tanggal Mulai</th>
                                        <th class="text-center">Tanggal Selesai</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Nama Slider</th>
                                        <th class="text-center">Description</th>
                                        <th class="text-center">Tanggal Mulai</th>
                                        <th class="text-center">Tanggal Selesai</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

            </section>
            <!-- /.content -->

            <div class="modal fade" id="modal-add-slider">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary" style="background-image: linear-gradient(to right bottom, #00C6FF, #0072FF)">
                            <h4 class="modal-title">Tambah Slider</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                        </div>
                        <form id="form-save-slider" method="post" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="slidername">Nama Slider</label>
                                            <input type="text" class="form-control" name="name" id="slider-name" placeholder="Slider Name">
                                        </div>
                                        <div class="form-group">
                                            <label for="sliderdescription">Deskripsi Slider</label>
                                            <input type="text" class="form-control" name="description" id="slider-description" placeholder="Slider Description">
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="startdate">Tanggal Mulai</label>
                                                    <input type="date" class="form-control" name="start_date" id="slider-start">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="enddate">Tanggal Selesai</label>
                                                    <input type="date" class="form-control" name="end_date" id="slider-end">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="icon">Foto Slider (Maksimal 5 MB) (Lebar: 750px dan Panjang: 252px)</label>
                                            <input type="file" class="form-control" name="picture" id="slider-picture" accept="image/png, image/jpeg, image/gif">
                                            <!-- <div class="custom-file">
                                                <label class="custom-file-label" for="customFile">Choose file</label>
                                            </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                <button type="submit" id="btn-save-slider" class="btn btn-primary">Tambah</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->

            <div class="modal fade" id="modal-detail-slider">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary" style="background-image: linear-gradient(to right bottom, #00C6FF, #0072FF)">
                            <h4 class="modal-title">Detail Slider</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <div class="card">
                                <div class="card-body">
                                    <img class="img-fluid img-thumbnail" id="img-slider" alt="foto slider">
                                    <table class="table table-bordered mt-3">
                                        <tr>
                                            <td>Nama Slider</td>
                                            <td id="det-slider-name"></td>
                                        </tr>
                                        <tr>
                                            <td>Deskripsi</td>
                                            <td id="det-slider-description"></td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal Mulai</td>
                                            <td id="det-slider-start"></td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal Selesai</td>
                                            <td id="det-slider-end"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->

            <div class="modal fade" id="modal-edit-slider">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary" style="background-image: linear-gradient(to right bottom, #00C6FF, #0072FF)">
                            <h4 class="modal-title">Edit Mitra</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                        </div>
                        <form id="form-edit-slider" method="post" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="card">
                                    <div class="card-body">
                                        <input type="hidden" name="slider_id" id="slider_id">
                                        <div class="form-group">
                                            <label for="slidername">Nama Slider</label>
                                            <input type="text" class="form-control" name="edit_slider_name" id="edit-slider-name" placeholder="Slider Name">
                                        </div>
                                        <div class="form-group">
                                            <label for="sliderdescription">Deskripsi Slider</label>
                                            <input type="text" class="form-control" name="edit_slider_description" id="edit-slider-description" placeholder="Slider Description">
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="startdate">Tanggal Mulai</label>
                                                    <input type="date" class="form-control" name="edit_slider_start_date" id="edit-slider-start">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="enddate">Tanggal Selesai</label>
                                                    <input type="date" class="form-control" name="edit_slider_end_date" id="edit-slider-end">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="icon">Foto Slider (jika diperlukan) (Maksimal 5 MB)</label>
                                            <input type="file" class="form-control" name="edit_slider_picture" id="edit-slider-picture" accept="image/png, image/jpeg, image/gif">
                                            <!-- <div class="custom-file">
                                                <label class="custom-file-label" for="customFile">Choose file</label>
                                            </div> -->
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
        