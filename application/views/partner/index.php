

            <!-- Main content -->
            <section class="content">

                <div class="card">
                    <div class="card-header">
                        <button type="button" data-toggle="modal" data-target="#modal-add-partner" class="btn btn-primary" style="background-image: linear-gradient(to right bottom, #00C6FF, #0072FF)"><i class="fas fa-plus-square"></i> Tambah Mitra</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="partner" class="table table-bordered table-striped" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Unique ID</th>
                                    <th class="text-center">Nama Toko</th>
                                    <th class="text-center">Nama Pemilik</th>
                                    <th class="text-center">Phone</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">Alamat</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Unique ID</th>
                                    <th class="text-center">Nama Toko</th>
                                    <th class="text-center">Nama Pemilik</th>
                                    <th class="text-center">Phone</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">Alamat</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

            </section>
            <!-- /.content -->

            <div class="modal fade" id="modal-add-partner">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary" style="background-image: linear-gradient(to right bottom, #00C6FF, #0072FF)">
                            <h4 class="modal-title">Tambah Mitra</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                        </div>
                        <form id="form-save-partner" type="post">
                            <div class="modal-body">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="nama-toko">Nama Toko</label>
                                            <input type="text" class="form-control" name="nama_toko" id="nama_toko" placeholder="Nama Toko">
                                        </div>
                                        <div class="form-group">
                                            <label for="nama-pemilik">Nama Pemilik</label>
                                            <input type="text" class="form-control" name="nama_pemilik" id="nama_pemilik" placeholder="Nama Pemilik">
                                        </div>
                                        <div class="form-group">
                                            <label for="phone">Phone</label>
                                            <input type="text" class="form-control" name="phone" id="phone" placeholder="No. Handphone">
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" name="email" id="email" placeholder="Email">
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Alamat</label>
                                            <input type="text" class="form-control" name="alamat" id="alamat" placeholder="Alamat">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" id="btn-save-partner" class="btn btn-primary">Tambah</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->

            <div class="modal fade" id="modal-detail-partner">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary" style="background-image: linear-gradient(to right bottom, #00C6FF, #0072FF)">
                            <h4 class="modal-title">Detail Mitra</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <div class="card">
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td width="30%">Unique ID</td>
                                            <td id="det-part-uniqueID"></td>
                                        </tr>
                                        <tr>
                                            <td>Nama Toko</td>
                                            <td id="det-part-nama-toko"></td>
                                        </tr>
                                        <tr>
                                            <td>Nama Pemilik</td>
                                            <td id="det-part-nama-pemilik"></td>
                                        </tr>
                                        <tr>
                                            <td>Phone</td>
                                            <td id="det-part-phone"></td>
                                        </tr>
                                        <tr>
                                            <td>Email</td>
                                            <td id="det-part-email"></td>
                                        </tr>
                                        <tr>
                                            <td>Alamat</td>
                                            <td id="det-part-alamat"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
        