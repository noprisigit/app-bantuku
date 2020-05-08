            <!-- Main content -->
            <section class="content">

                <div class="card">
                    <div class="card-header">
                        <button type="button" data-toggle="modal" data-target="#modal-add-product" class="btn btn-primary" style="background-image: linear-gradient(to right bottom, #00C6FF, #0072FF)"><i class="fas fa-plus-square"></i> Tambah Product</button>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="product" class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Unique ID</th>
                                        <th class="text-center">Nama Produk</th>
                                        <th class="text-center">Harga per Satuan</th>
                                        <th class="text-center">Berat</th>
                                        <th class="text-center">Stock</th>
                                        <th class="text-center">Kategori</th>
                                        <th class="text-center">Nama Toko</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <!-- <tfoot>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Unique ID</th>
                                        <th class="text-center">Nama Produk</th>
                                        <th class="text-center">Harga per Satuan</th>
                                        <th class="text-center">Berat</th>
                                        <th class="text-center">Stock</th>
                                        <th class="text-center">Kategori</th>
                                        <th class="text-center">Nama Toko</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </tfoot> -->
                            </table>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

            </section>
            <!-- /.content -->

            <div class="modal fade" id="modal-add-product">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header bg-primary" style="background-image: linear-gradient(to right bottom, #00C6FF, #0072FF)">
                            <h4 class="modal-title">Tambah Product</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                        </div>
                        <form id="form-save-product" method="post" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">Nama Produk</label>
                                                    <input type="text" class="form-control" name="product_name" id="product_name_save" placeholder="Nama Produk">
                                                </div>
                                                <div class="form-group">
                                                    <label for="">Harga Produk Per Satuan</label>
                                                    <input type="number" class="form-control" name="product_price" id="product_price_save" placeholder="Harga Produk Per Satuan">
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="">Stok Produk</label>
                                                            <input type="number" class="form-control" name="product_stock" id="product_stock_save" placeholder="Stok Produk">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="">Berat Produk (gram)</label>
                                                            <input type="number" class="form-control" name="product_weight" id="product_weight_save" placeholder="Berat Produk">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">Kategori</label>
                                                    <select class="form-control select2bs4" name="product_category" id="product_category_save">
                                                        <option selected="selected" disabled="disabled">Kategori Produk</option>
                                                        <?php foreach($categories as $item) : ?>
                                                            <option value="<?= $item['CategoryID'] ?>"><?= $item['CategoryName'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="">Nama Toko</label>
                                                    <select class="form-control select2bs4" name="product_partner" id="product_partner_save">
                                                        <option selected="selected" disabled="disabled">Nama Toko</option>
                                                        <?php foreach($partners as $item) : ?>
                                                            <option value="<?= $item['PartnerID'] ?>"><?= $item['CompanyName'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="">Foto Produk (Maksimal 5 MB)</label>
                                                    <input type="file" class="form-control" name="product_image" id="product_image_save" accept="image/png, image/jpeg">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Deskripsi Produk</label>
                                            <textarea class="form-control" name="product_desc" id="product_desc_save" cols="30" rows="5" placeholder="Deskripsi Produk"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" id="btn-save-product" class="btn btn-primary">Tambah</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->

            <div class="modal fade" id="modal-detail-product">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header bg-primary" style="background-image: linear-gradient(to right bottom, #00C6FF, #0072FF)">
                            <h4 class="modal-title">Detail Product</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-center">
                                        <img class="img-fluid img-thumbnail" id="img-product" alt="Product Image">
                                    </div>
                                    <table class="table table-bordered mt-3">
                                        <tr>
                                            <td width="30%">Unique ID</td>
                                            <td id="det-product-uniqueID">Loading...</td>
                                        </tr>
                                        <tr>
                                            <td>Nama Produk</td>
                                            <td id="det-product-name">Loading...</td>
                                        </tr>
                                        <tr>
                                            <td>Harga per Satuan</td>
                                            <td id="det-product-price">Loading...</td>
                                        </tr>
                                        <tr>
                                            <td>Stock</td>
                                            <td id="det-product-stock">Loading...</td>
                                        </tr>
                                        <tr>
                                            <td>Berat</td>
                                            <td id="det-product-weight">Loading...</td>
                                        </tr>
                                        <tr>
                                            <td>Nama Toko</td>
                                            <td id="det-product-shop">Loading...</td>
                                        </tr>
                                        <tr>
                                            <td>Kategori</td>
                                            <td id="det-product-category">Loading...</td>
                                        </tr>
                                        <tr>
                                            <td>Deskripsi</td>
                                            <td id="det-product-desc">Loading...</td>
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