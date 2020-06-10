            <!-- Main content -->
            <section class="content">
					<div class="card">
						<div class="card-header">
							<button type="button" data-toggle="modal" data-target="#modal-add-product" class="btn btn-primary" style="background-image: linear-gradient(to right bottom, #00C6FF, #0072FF)"><i class="fas fa-plus-square"></i> Tambah Produk</button>
						</div>
						<div class="card-body">
							<div class="table-responsive">
								<table id="product" class="table table-bordered table-striped" width="100%">
									<thead>
										<tr>
											<th class="text-center" style="vertical-align: middle; text-align: center;">#</th>
											<th class="text-center" style="vertical-align: middle; text-align: center;">Nama Produk</th>
											<th class="text-center" style="vertical-align: middle; text-align: center;">Harga Satuan</th>
											<th class="text-center" style="vertical-align: middle; text-align: center;">Stock</th>
											<th class="text-center" style="vertical-align: middle; text-align: center;">Nama Toko</th>
											<th class="text-center" style="vertical-align: middle; text-align: center;">Status Produk</th>
											<th class="text-center" style="vertical-align: middle; text-align: center;">Status Promo Produk</th>
											<th class="text-center" style="vertical-align: middle; text-align: center;">Actions</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</div>
					</div>
            </section>

            <div class="modal fade" id="modal-add-product">
					<div class="modal-dialog modal-xl">
						<div class="modal-content">
							<div class="modal-header bg-primary" style="background-image: linear-gradient(to right bottom, #00C6FF, #0072FF)">
								<h4 class="modal-title">Tambah Produk</h4>
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
																<label for="">Harga Produk Per Satuan (Sebelum Pajak)</label>
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
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
									<button type="submit" id="btn-save-product" class="btn btn-primary">Tambah</button>
								</div>
							</form>
						</div>
					</div>
            </div>
            <!-- /.modal -->

            <div class="modal fade" id="modal-detail-product">
                <div class="modal-dialog modal-xl modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header bg-primary" style="background-image: linear-gradient(to right bottom, #00C6FF, #0072FF)">
                            <h4 class="modal-title">Detail Produk</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
									<div class="card">
										<div class="card-body">
											<div class="row">
												<div class="col-md-3"></div>
												<div class="col-md-5">
													<h6 class="text-bold">Detail Produk</h6>
												</div>
												<div class="col-md-4">
													<h6 class="text-bold">Status Promo</h6>
												</div>
											</div>
											<div class="row">
													<div class="col-md-3 d-flex justify-content-center align-items-center">
														<img class="img-fluid img-thumbnail center-block text-center" id="img-product" alt="Product Image">
													</div>
													<div class="col-md-5">                                                            
														<table class="table table-bordered">
															<tr>
																	<td width="30%">Unique ID</td>
																	<td id="det-product-uniqueID">Loading...</td>
															</tr>
															<tr>
																	<td>Nama Produk</td>
																	<td id="det-product-name">Loading...</td>
															</tr>
															<tr>
																	<td>Harga Satuan</td>
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
													<div class="col-md-4">
														<table class="table table-bordered">
															<tr>
																<td width="50%">Status Promo Produk</td>
																<td id="det-product-status-promo">Loading...</td>
															</tr>
															<tr>
																<td width="50%">Nilai Promo Produk</td>
																<td id="det-product-nilai-promo">Loading...</td>
															</tr>
															<tr>
																<td>Tanggal Mulai Promo</td>
																<td id="det-product-start-promo">Loading...</td>
															</tr>
															<tr>
																<td>Tanggal Selesai Promo</td>
																<td id="det-product-end-promo">Loading...</td>
															</tr>
														</table>
													</div>
											</div>
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

            <div class="modal fade" id="modal-edit-product">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header bg-primary" style="background-image: linear-gradient(to right bottom, #00C6FF, #0072FF)">
                            <h4 class="modal-title">Edit Produk</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                        </div>
                        <form id="form-edit-product" method="post" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="">Unique ID</label>
                                            <input type="text" class="form-control" name="product_unique_id" id="product_unique_edit" placeholder="Nama Produk" readonly>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">Nama Produk</label>
                                                    <input type="text" class="form-control" name="product_name" id="product_name_edit" placeholder="Nama Produk">
                                                </div>
                                                <div class="form-group">
                                                    <label for="">Harga Produk Per Satuan</label>
                                                    <input type="number" class="form-control" name="product_price" id="product_price_edit" placeholder="Harga Produk Per Satuan">
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="">Stok Produk</label>
                                                            <input type="number" class="form-control" name="product_stock" id="product_stock_edit" placeholder="Stok Produk">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="">Berat Produk (gram)</label>
                                                            <input type="number" class="form-control" name="product_weight" id="product_weight_edit" placeholder="Berat Produk">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">Kategori</label>
                                                    <select class="form-control select2bs4" name="product_category" id="product_category_edit">
                                                        <option selected="selected" disabled="disabled">Kategori Produk</option>
                                                        <?php foreach($categories as $item) : ?>
                                                            <option value="<?= $item['CategoryID'] ?>"><?= $item['CategoryName'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="">Nama Toko</label>
                                                    <select class="form-control select2bs4" name="product_partner" id="product_partner_edit">
                                                        <option selected="selected" disabled="disabled">Nama Toko</option>
                                                        <?php foreach($partners as $item) : ?>
                                                            <option value="<?= $item['PartnerID'] ?>"><?= $item['CompanyName'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="">Foto Produk (*Jika Diperlukan) (Maksimal 5 MB)</label>
                                                    <input type="file" class="form-control" name="product_image" id="product_image_edit" accept="image/png, image/jpeg">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Deskripsi Produk</label>
                                            <textarea class="form-control" name="product_desc" id="product_desc_edit" cols="30" rows="5" placeholder="Deskripsi Produk"></textarea>
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

            <div class="modal fade" id="modal-add-product-promo">
                <div class="modal-dialog modal-xl modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header bg-primary" style="background-image: linear-gradient(to right bottom, #00C6FF, #0072FF)">
                            <h4 class="modal-title">Tambah Promo Produk</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
									<div class="row">
										<div class="col-md-8">
											<div class="card">
												<div class="card-body">
													<div class="row">
															<div class="col-md-4">
																<img class="img-fluid img-thumbnail promo-img-product" id="promo-img-product" width="256px" alt="Product Image">
															</div>
															<div class="col-md-8">
																<table class="table table-bordered">
																	<tr>
																			<td width="30%">Unique ID</td>
																			<td class="promo-product-uniqueID">Loading...</td>
																	</tr>
																	<tr>
																			<td>Nama Produk</td>
																			<td class="promo-product-name">Loading...</td>
																	</tr>
																	<tr>
																			<td>Harga per Satuan</td>
																			<td class="promo-product-price">Loading...</td>
																	</tr>
																	<tr>
																			<td>Stock</td>
																			<td class="promo-product-stock">Loading...</td>
																	</tr>
																	<tr>
																			<td>Berat</td>
																			<td class="promo-product-weight">Loading...</td>
																	</tr>
																	<tr>
																			<td>Nama Toko</td>
																			<td class="promo-product-shop">Loading...</td>
																	</tr>
																	<tr>
																			<td>Kategori</td>
																			<td class="promo-product-category">Loading...</td>
																	</tr>
																	<tr>
																			<td>Deskripsi</td>
																			<td class="promo-product-desc">Loading...</td>
																	</tr>
																</table>
															</div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="card">
												<div class="card-body">
													<form id="form-add-product-promo" method="post">
														<input type="hidden" name="product_uniqueID" id="promo_uniqueID">
														<div class="form-group">
															<label for="">Tambahkan Nilai Promo (dalam Persen)</label>
															<input type="number" class="form-control" name="product_nilai_promo" id="product_nilai_promo" placeholder="Besar Nilai Promo dalam Persen">
														</div>
														<div class="form-group">
															<label for="">Tanggal Mulai Promo</label>
															<input type="date" class="form-control" name="product_tanggal_mulai_promo" id="product_tanggal_mulai_promo">
														</div>
														<div class="form-group">
															<label for="">Tanggal Selesai Promo</label>
															<input type="date" class="form-control" name="product_tanggal_selesai_promo" id="product_tanggal_selesai_promo">
														</div>
														<div class="form-group">
															<button type="submit" id="btn-submit-promo" class="btn btn-primary btn-block">Tambah Promo</button>
														</div>														
													</form>
												</div>
											</div>
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

            <div class="modal fade" id="modal-edit-product-promo">
					<div class="modal-dialog modal-xl modal-dialog-scrollable">
						<div class="modal-content">
							<div class="modal-header bg-primary" style="background-image: linear-gradient(to right bottom, #00C6FF, #0072FF)">
								<h4 class="modal-title">Edit Promo Produk</h4>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span></button>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="col-md-8">
										<div class="card">
											<div class="card-body">
												<div class="row">
													<div class="col-md-4">
														<img class="img-fluid img-thumbnail promo-img-product" id="promo-img-product" width="256px" alt="Product Image">
													</div>
													<div class="col-md-8">
														<table class="table table-bordered">
															<tr>
																<td width="30%">Unique ID</td>
																<td class="promo-product-uniqueID">Loading...</td>
															</tr>
															<tr>
																<td>Nama Produk</td>
																<td class="promo-product-name">Loading...</td>
															</tr>
															<tr>
																<td>Harga per Satuan</td>
																<td class="promo-product-price">Loading...</td>
															</tr>
															<tr>
																<td>Stock</td>
																<td class="promo-product-stock">Loading...</td>
															</tr>
															<tr>
																<td>Berat</td>
																<td class="promo-product-weight">Loading...</td>
															</tr>
															<tr>
																<td>Nama Toko</td>
																<td class="promo-product-shop">Loading...</td>
															</tr>
															<tr>
																<td>Kategori</td>
																<td class="promo-product-category">Loading...</td>
															</tr>
															<tr>
																<td>Deskripsi</td>
																<td class="promo-product-desc">Loading...</td>
															</tr>
														</table>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="card">
											<div class="card-body">
												<form id="form-edit-product-promo" method="post">
													<input type="hidden" name="product_uniqueID" id="edt_promo_uniqueID">
													<div class="form-group">
														<label for="">Edit Nilai Promo (dalam Persen)</label>
														<input type="number" class="form-control" name="product_nilai_promo" id="edt_product_nilai_promo" placeholder="Besar Nilai Promo dalam Persen">
													</div>
													<div class="form-group">
														<label for="">Edit Tanggal Mulai Promo</label>
														<input type="date" class="form-control" name="product_tanggal_mulai_promo" id="edt_product_tanggal_mulai_promo">
													</div>
													<div class="form-group">
														<label for="">Edit Tanggal Selesai Promo</label>
														<input type="date" class="form-control" name="product_tanggal_selesai_promo" id="edt_product_tanggal_selesai_promo">
													</div>
													<div class="form-group">
														<button type="submit" id="btn-edit-promo" class="btn btn-primary btn-block">Perbaharui Promo</button>
													</div>
												</form>
											</div>
										</div>
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

				<div class="modal fade" id="modal-tambah-stok-product">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary" style="background-image: linear-gradient(to right bottom, #00C6FF, #0072FF)">
                            <h4 class="modal-title">Tambah Stok Produk</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
									<div class="card">
										<div class="card-body">
											<div class="row">
												<div class="col-md-4">
													<img class="img-fluid img-thumbnail promo-img-product" id="promo-img-product" width="256px" alt="Product Image">
												</div>
												<div class="col-md-8">
													<table class="table table-bordered">
														<tr>
															<td width="30%">Unique ID</td>
															<td class="promo-product-uniqueID">Loading...</td>
														</tr>
														<tr>
															<td>Nama Produk</td>
															<td class="promo-product-name">Loading...</td>
														</tr>
														<tr>
															<td>Nama Toko</td>
															<td class="promo-product-shop">Loading...</td>
														</tr>
														<tr>
															<td>Stock</td>
															<td class="promo-product-stock">Loading...</td>
														</tr>
													</table>
												</div>
											</div>
											<div class="row justify-content-center">
												<div class="col-md-1">
													<button type="button" class="btn btn-success minStok float-right" disabled="disabled"><i class="fas fa-minus"></i></button>
												</div>
												<div class="col-md-2">
													<div class="form-group">
														<input type="number" class="form-control jumlahStock" value="0" readonly>
													</div>
												</div>
												<div class="col-md-1">
													<button type="button" class="btn btn-success plusStok"><i class="fas fa-plus"></i></button>
												</div>
											</div>
											<div class="row justify-content-center">
												<div class="col-md-4">
												<button type="button" class="btn btn-primary btn-block btn-submit-tambah-stock" disabled>Proses</button>
												</div>
											</div>
										</div>
									</div>
                        </div>
                        <div class="modal-footer justify-content-end">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->

				<div class="modal fade" id="modalTambahToko">
					<div class="modal-dialog modal-xl">
						<div class="modal-content">
							<div class="modal-header bg-primary" style="background-image: linear-gradient(to right bottom, #00C6FF, #0072FF)">
								<h4 class="modal-title">Tambah Toko</h4>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span></button>
							</div>
							<form id="frmSaveToko" type="post">
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
																<label for="email">Gambar Toko (Maksimal 5 MB)</label>
																<input type="file" class="form-control" name="gambar_toko" id="gambar_toko">
														</div>
													</div>
												</div>
										</div>
									</div>
								</div>
								<div class="modal-footer justify-content-between">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
									<button type="submit" id="btn-save-partner" class="btn btn-primary">Tambah</button>
								</div>
							</form>
						</div>
						<!-- /.modal-content -->
					</div>
					<!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->