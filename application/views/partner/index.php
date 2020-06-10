            <section class="content">
					<div class="card">
						<div class="card-header">
							<button type="button" data-toggle="modal" data-target="#modal-add-partner" class="btn btn-primary" style="background-image: linear-gradient(to right bottom, #00C6FF, #0072FF)"><i class="fas fa-plus-square"></i> Tambah Toko</a>
						</div>
						<div class="card-body">
							<div class="table-responsive">
								<table id="partner" class="table table-bordered table-striped" width="100%">
									<thead>
										<tr>
											<th class="text-center">#</th>
											<th class="text-center">Nama Toko</th>
											<th class="text-center">Nama Pemilik</th>
											<th class="text-center">Phone</th>
											<th class="text-center">Email</th>
											<th class="text-center">Actions</th>
										</tr>
									</thead>
									<tbody></tbody>
									<tfoot>
										<tr>
											<th class="text-center">#</th>
											<th class="text-center">Nama Toko</th>
											<th class="text-center">Nama Pemilik</th>
											<th class="text-center">Phone</th>
											<th class="text-center">Email</th>
											<th class="text-center">Actions</th>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
            </section>

            <div class="modal fade" id="modal-add-partner">
					<div class="modal-dialog modal-xl">
						<div class="modal-content">
							<div class="modal-header bg-primary" style="background-image: linear-gradient(to right bottom, #00C6FF, #0072FF)">
								<h4 class="modal-title">Tambah Toko</h4>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span></button>
							</div>
							<form id="form-save-partner" type="post">
								<div class="modal-body">
									<div class="card mb-0">
										<div class="card-body">
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label for="nama-toko">Nama Toko</label>
														<input type="text" class="form-control" name="nama_toko" id="nama_toko" placeholder="Nama Toko">
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
													<div class="row">
														<div class="col-md-4">
															<div class="form-group">
																<label for="phone">Kode Pos</label>
																<input type="text" class="form-control" name="kode_pos" id="kode-pos" placeholder="Kode Pos">
															</div>
														</div>
														<div class="col-md-8">
															<div class="form-group">
																<label for="email">Gambar Toko (Maksimal 5 MB)</label>
																<input type="file" class="form-control" name="gambar_toko" id="gambar_toko">
															</div>
														</div>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label for="nama-pemilik">Nama Pemilik</label>
														<input type="text" class="form-control" name="nama_pemilik" id="nama_pemilik" placeholder="Nama Pemilik">
													</div>
													<div class="row">
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
														</div>
														<div class="col-md-6">
															<div class="form-group">
																<label for="nama-pemilik">Kabupaten</label>
																<select class="form-control select2bs4 district" name="kabupaten" id="kabupaten" style="width: 100%;">
																	<option selected="selected" disabled>Kabupaten</option>
																</select>
															</div>
														</div>
													</div>
													<div class="row">
														<div class="col-md-6">
															<div class="form-group">
																<label for="">Latitude</label>
																<input type="text" class="form-control" name="latitude" id="latitude" placeholder="Latitude boleh dikosongkan">
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group">
																<label for="">Longitude</label>
																<input type="text" class="form-control" name="longitude" id="longitude" placeholder="Longitude boleh dikosongkan">
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label for="email">Alamat</label>
												<input type="text" class="form-control" name="alamat" id="alamat" placeholder="Alamat">
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
					</div>
            </div>

            <div class="modal fade" id="modal-detail-partner">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header bg-primary" style="background-image: linear-gradient(to right bottom, #00C6FF, #0072FF)">
                            <h4 class="modal-title">Detail Toko</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
									<div class="card mb-0">
										<div class="card-body">
											<div class="row">
												<div class="col-md-3 d-flex justify-content-center">
													<img src="" class="img-fluid img-thumbnail" width="256" id="shop-picture" alt="Gambar Toko">
												</div>
												<div class="col-md-5">
													<table class="table table-bordered">
														<tr>
															<td class="text-bold" width="30%">Unique ID</td>
															<td id="det-part-uniqueID"></td>
														</tr>
														<tr>
															<td class="text-bold">Nama Toko</td>
															<td id="det-part-nama-toko"></td>
														</tr>
														<tr>
															<td class="text-bold">Nama Pemilik</td>
															<td id="det-part-nama-pemilik"></td>
														</tr>
														<tr>
															<td class="text-bold">Phone</td>
															<td id="det-part-phone"></td>
														</tr>
														<tr>
															<td class="text-bold">Email</td>
															<td id="det-part-email"></td>
														</tr>
													</table>
												</div>
												<div class="col-md-4">
													<table class="table table-bordered">
														<tr>
															<td class="text-bold">Latitude</td>
															<td id="det-part-latitude"></td>
														</tr>
														<tr>
															<td class="text-bold">Longitude</td>
															<td id="det-part-longitude"></td>
														</tr>
														<tr>
															<td class="text-bold">Alamat</td>
															<td id="det-part-alamat"></td>
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

            <div class="modal fade" id="modal-edit-partner">
					<div class="modal-dialog modal-xl">
						<div class="modal-content">
							<div class="modal-header bg-primary" style="background-image: linear-gradient(to right bottom, #00C6FF, #0072FF)">
								<h4 class="modal-title">Edit Toko</h4>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span></button>
							</div>
							<form id="form-edit-partner" method="post" enctype="multipart/form-data">
								<div class="modal-body">
									<div class="card mb-0">
										<div class="card-body">
											<input type="hidden" name="partner_id" id="partner_id">
											<div class="row">
												<div class="col-md-4">
													<div class="form-group">
														<label for="name">UniqueID</label>
														<input type="text" class="form-control" name="partner_uniqueid_edit" id="partner_uniqueid_edit" placeholder="Unique ID" readonly>
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<label for="description">Nama Toko</label>
														<input type="text" class="form-control" name="partner_nama_toko_edit" id="partner_nama_toko_edit" placeholder="Nama Toko">
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<label for="description">Nama Pemilik</label>
														<input type="text" class="form-control" name="partner_nama_pemilik_edit" id="partner_nama_pemilik_edit" placeholder="Nama Pemilik">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">    
													
													<div class="row">
														<div class="col-md-6">
															<div class="form-group">
																<label for="description">No. Handphone</label>
																<input type="text" class="form-control" name="partner_phone_edit" id="partner_phone_edit" placeholder="Phone">
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group">
																<label for="description">Email</label>
																<input type="text" class="form-control" name="partner_email_edit" id="partner_email_edit" placeholder="Email">
															</div>
														</div>
													</div>
													<div class="row">
														<div class="col-md-4">
															<div class="form-group">
																<label for="phone">Kode Pos</label>
																<input type="text" class="form-control" name="partner_kode_pos_edit" id="partner_kode_pos_edit" placeholder="Kode Pos">
															</div>
														</div>
														<div class="col-md-8">		
															<div class="form-group">
																<label for="email">Gambar Toko (Jika Diperlukan) (Maksimal 5 MB)</label>
																<input type="file" class="form-control" name="partner_gambar_toko_edit" id="partner_gambar_toko_edit">
															</div>
														</div>
													</div>
												</div>
												<div class="col-md-6">
													<div class="row">
														<div class="col-md-6">
															<div class="form-group">
																<label for="nama-toko">Provinsi</label>
																<select class="form-control select2bs4 province" name="partner_provinsi_edit" id="partner_provinsi_edit" style="width: 100%;">
																	<option selected="selected" disabled>Provinsi</option>
																	<?php foreach ($provinsi as $item) : ?>
																		<option value="<?= $item['ProvinceID'] ?>"><?= $item['ProvinceName'] ?></option>
																	<?php endforeach; ?>                   
																</select>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group">
																<label for="nama-pemilik">Kabupaten</label>
																<select class="form-control select2bs4 district" name="partner_kabupaten_edit" id="partner_kabupaten_edit" style="width: 100%;">
																	<option selected="selected" disabled>Kabupaten</option>
																</select>
															</div>
														</div>
													</div>
													<div class="row">
														<div class="col-md-6">
															<div class="form-group">															
																<label for="">Latitude</label>
																<input type="text" class="form-control" name="partner_latitude_edit" id="partner_latitude_edit" placeholder="Latitude boleh dikosongkan">
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group">															
																<label for="">Longitude</label>
																<input type="text" class="form-control" name="partner_longitude_edit" id="partner_longitude_edit" placeholder="Longitude boleh dikosongkan">
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label for="description">Alamat</label>
												<input type="text" class="form-control" name="partner_alamat_edit" id="partner_alamat_edit" placeholder="Alamat">
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
					</div>
            </div>        