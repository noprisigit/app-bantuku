         <section class="content">
            <div class="card">
               <div class="card-header">
                  <button type="button" data-toggle="modal" data-target="#modal-add-customer" class="btn btn-primary" style="background-image: linear-gradient(to right bottom, #00C6FF, #0072FF)"><i class="fas fa-plus-square"></i> Tambah Customer</a>
               </div>
               <div class="card-body">
                  <div class="table-responsive">
                     <table id="customer" class="table table-bordered table-striped nowrap" width="100%">
                        <thead>
                           <tr>
                              <th class="text-center">#</th>
                              <th class="text-center">Unique ID</th>
                              <th class="text-center">Nama Customer</th>
                              <th class="text-center">Email</th>
                              <th class="text-center">No. Telp</th>
                              <th class="text-center">Status Email</th>
                              <th class="text-center">Actions</th>
                           </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                           <tr>
                              <th class="text-center">#</th>
                              <th class="text-center">Unique ID</th>
                              <th class="text-center">Nama Customer</th>
                              <th class="text-center">Email</th>
                              <th class="text-center">No. Telp</th>
                              <th class="text-center">Status Email</th>
                              <th class="text-center">Actions</th>
                           </tr>
                        </tfoot>
                     </table>
                  </div>
               </div>
            </div>
         </section>

         <div class="modal fade" id="modal-add-customer">
            <div class="modal-dialog modal-lg">
               <div class="modal-content">
                  <div class="modal-header bg-primary" style="background-image: linear-gradient(to right bottom, #00C6FF, #0072FF)">
                        <h4 class="modal-title">Tambah Customer</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span></button>
                  </div>
                  <form id="form-save-customer" type="post">
                     <div class="modal-body">
                        <div class="card">
                           <div class="card-body">
                              <div class="form-group">
                                 <label for="">Nama Customer</label>
                                 <input type="text" class="form-control" name="customerName" id="customerName" placeholder="Nama Customer">
                              </div>
                              <div class="row">
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label for="">Email</label>
                                       <input type="email" class="form-control" name="customerEmail" id="customerEmail" placeholder="Email">
                                    </div>                                 
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label for="">No. Telphone</label>
                                       <input type="number" class="form-control" name="customerPhone" id="customerPhone" placeholder="No. Telphone">
                                    </div>                                 
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label for="">Password</label>
                                       <input type="password" class="form-control" name="customerPass" id="customerPass" placeholder="Password">
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label for="">Konfirmasi Password</label>
                                       <input type="password" class="form-control" name="customerConfirmPass" id="customerConfirmPass" placeholder="Konfirmasi Password">
                                    </div>                                 
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label for="">Alamat Lengkap</label>
                                 <textarea class="form-control" name="customerAddress" id="customerAddress" cols="20" rows="5" placeholder="Alamat Lengkap"></textarea>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                     </div>
                  </form>
               </div>
            </div>
         </div>

         <div class="modal fade" id="modal-detail-customer">
            <div class="modal-dialog modal-lg">
               <div class="modal-content">
                  <div class="modal-header bg-primary" style="background-image: linear-gradient(to right bottom, #00C6FF, #0072FF)">
                        <h4 class="modal-title">Detail Customer</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span></button>
                  </div>
                  <div class="modal-body">
                     <div class="card">
                        <div class="card-body">
                           <table class="table">
                              <tr>
                                 <td class="text-bold" width="30%">UniqueID</td>
                                 <td id="detCustomerUniqID"></td>
                              </tr>
                              <tr>
                                 <td class="text-bold">Nama Customer</td>
                                 <td id="detCustomerName"></td>
                              </tr>
                              <tr>
                                 <td class="text-bold">Email</td>
                                 <td id="detCustomerEmail"></td>
                              </tr>
                              <tr>
                                 <td class="text-bold">No. Telphone</td>
                                 <td id="detCustomerPhone"></td>
                              </tr>
                              <tr>
                                 <td class="text-bold">Alamat</td>
                                 <td id="detCustomerAddress"></td>
                              </tr>
                              <tr>
                                 <td class="text-bold">Status Email</td>
                                 <td id=detCustomerEmailVerified></td>
                              </tr>
                              <tr>
                                 <td class="text-bold">Tanggal Registrasi</td>
                                 <td id="detCustomerRegistrationDate"></td>
                              </tr>
                           </table>
                        </div>
                     </div>
                  </div>
                  <div class="modal-footer justify-content-between">
                     <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                  </div>
               </div>
            </div>
         </div>

         <div class="modal fade" id="modal-edit-customer">
            <div class="modal-dialog modal-lg">
               <div class="modal-content">
                  <div class="modal-header bg-primary" style="background-image: linear-gradient(to right bottom, #00C6FF, #0072FF)">
                        <h4 class="modal-title">Edit Customer</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span></button>
                  </div>
                  <form id="form-edit-customer" type="post">
                     <div class="modal-body">
                        <div class="card">
                           <div class="card-body">
                              <div class="form-group">
                                 <label for="">Unique ID</label>
                                 <input type="text" class="form-control" name="edtCustomerUniqueID" id="edtCustomerUniqueID" placeholder="Unique ID" readonly>
                              </div>
                              <div class="form-group">
                                 <label for="">Nama Customer</label>
                                 <input type="text" class="form-control" name="edtCustomerName" id="edtCustomerName" placeholder="Nama Customer">
                              </div>
                              <div class="row">
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label for="">Email</label>
                                       <input type="email" class="form-control" name="edtCustomerEmail" id="edtCustomerEmail" placeholder="Email">
                                    </div>                                 
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label for="">No. Telphone</label>
                                       <input type="number" class="form-control" name="edtCustomerPhone" id="edtCustomerPhone" placeholder="No. Telphone">
                                    </div>                                 
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label for="">Alamat Lengkap</label>
                                 <textarea class="form-control" name="edtCustomerAddress" id="edtCustomerAddress" cols="20" rows="5" placeholder="Alamat Lengkap"></textarea>
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
