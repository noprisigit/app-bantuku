

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
                <div class="modal-dialog modal-xl">
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
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="nama-toko">Nama Toko</label>
                                                    <input type="text" class="form-control" name="nama_toko" id="nama_toko" placeholder="Nama Toko">
                                                </div>
                                                <div class="form-group">
                                                    <label for="nama-pemilik">Nama Pemilik</label>
                                                    <input type="text" class="form-control" name="nama_pemilik" id="nama_pemilik" placeholder="Nama Pemilik">
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="phone">Phone</label>
                                                            <input type="text" class="form-control" name="phone" id="phone" placeholder="No. Handphone">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="email">Email</label>
                                                            <input type="email" class="form-control" name="email" id="email" placeholder="Email">
                                                        </div>
                                                    </div>
                                                </div>                                                
                                                <div class="form-group">
                                                    <label for="email">Alamat</label>
                                                    <input type="text" class="form-control" name="alamat" id="alamat" placeholder="Alamat">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="nama-toko">Provinsi</label>
                                                    <select class="form-control select2bs4 province" name="provinsi" id="provinsi" style="width: 100%;">
                                                        <option selected="selected" disabled>Provinsi</option>
                                                        <?php foreach ($provinsi as $item) : ?>
                                                            <option value="<?= $item['ProvinceID'] ?>"><?= $item['ProvinceName'] ?></option>
                                                        <?php endforeach; ?>                   
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="nama-pemilik">Kabupaten</label>
                                                    <select class="form-control select2bs4 district" name="kabupaten" id="kabupaten" style="width: 100%;">
                                                        <option selected="selected" disabled>Kabupaten</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="phone">Kode Pos</label>
                                                    <input type="text" class="form-control" name="kode_pos" id="kode-pos" placeholder="Kode Pos">
                                                </div>
                                                <div class="form-group">
                                                    <label for="email">Gambar Toko</label>
                                                    <input type="file" class="form-control" name="gambar_toko" id="gambar_toko">
                                                </div>
                                            </div>
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
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header bg-primary" style="background-image: linear-gradient(to right bottom, #00C6FF, #0072FF)">
                            <h4 class="modal-title">Detail Mitra</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 d-flex justify-content-center">
                                            <img src="" class="img-fluid img-thumbnail" width="256" id="shop-picture" alt="Gambar Toko">
                                        </div>
                                        <div class="col-md-8">
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

            <div class="modal fade" id="modal-edit-partner">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header bg-primary" style="background-image: linear-gradient(to right bottom, #00C6FF, #0072FF)">
                            <h4 class="modal-title">Edit Mitra</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                        </div>
                        <form id="form-edit-partner" method="post" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="card">
                                    <div class="card-body">
                                        <input type="hidden" name="partner_id" id="partner_id">
                                        <div class="row">
                                            <div class="col-md-6">    
                                                <div class="form-group">
                                                    <label for="name">UniqueID</label>
                                                    <input type="text" class="form-control" name="partner_uniqueid_edit" id="partner_uniqueid_edit" placeholder="Unique ID" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label for="description">Nama Toko</label>
                                                    <input type="text" class="form-control" name="partner_nama_toko_edit" id="partner_nama_toko_edit" placeholder="Nama Toko">
                                                </div>
                                                <div class="form-group">
                                                    <label for="description">Nama Pemilik</label>
                                                    <input type="text" class="form-control" name="partner_nama_pemilik_edit" id="partner_nama_pemilik_edit" placeholder="Nama Pemilik">
                                                </div>
                                                <div class="form-group">
                                                    <label for="description">No. Handphone</label>
                                                    <input type="text" class="form-control" name="partner_phone_edit" id="partner_phone_edit" placeholder="Phone">
                                                </div>
                                                <div class="form-group">
                                                    <label for="description">Email</label>
                                                    <input type="text" class="form-control" name="partner_email_edit" id="partner_email_edit" placeholder="Email">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="description">Alamat</label>
                                                    <input type="text" class="form-control" name="partner_alamat_edit" id="partner_alamat_edit" placeholder="Alamat">
                                                </div>
                                                <div class="form-group">
                                                    <label for="nama-toko">Provinsi</label>
                                                    <select class="form-control select2bs4 province" name="partner_provinsi_edit" id="partner_provinsi_edit" style="width: 100%;">
                                                        <option selected="selected" disabled>Provinsi</option>
                                                        <?php foreach ($provinsi as $item) : ?>
                                                            <option value="<?= $item['ProvinceID'] ?>"><?= $item['ProvinceName'] ?></option>
                                                        <?php endforeach; ?>                   
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="nama-pemilik">Kabupaten</label>
                                                    <select class="form-control select2bs4 district" name="partner_kabupaten_edit" id="partner_kabupaten_edit" style="width: 100%;">
                                                        <option selected="selected" disabled>Kabupaten</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="phone">Kode Pos</label>
                                                    <input type="text" class="form-control" name="partner_kode_pos_edit" id="partner_kode_pos_edit" placeholder="Kode Pos">
                                                </div>
                                                <div class="form-group">
                                                    <label for="email">Gambar Toko (Jika Diperlukan)</label>
                                                    <input type="file" class="form-control" name="partner_gambar_toko_edit" id="partner_gambar_toko_edit">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Perbaharui</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
        