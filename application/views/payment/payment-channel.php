<section class="content">
   <div class="container-fluid">
      <button class="btn btn-primary" data-toggle="modal" data-target="#modalTambahPaymentChannel">Tambah Payment Channel</button>
      <div class="row mt-2">
         <div class="col-md-7">
            <div class="card">
               <div class="card-body p-0">
                  <table class="table">
                     <thead>
                        <tr>
                           <th style="width: 10px;">#</th>
                           <th>Kode</th>
                           <th>Payment Channel</th>
                           <th>Logo</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                     <tbody id="listPaymentChannel">
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>

<div class="modal fade" id="modalTambahPaymentChannel">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header bg-primary" style="background-image: linear-gradient(to right bottom, #00C6FF, #0072FF)">
            <h4 class="modal-title">Tambah Payment Channel</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span></button>
         </div>
         <form id="formSavePaymentChannel" type="post" enctype="multipart/form-data">
            <div class="modal-body">
               <div class="form-group">
                  <label for="">Kode Payment Channel</label>
                  <input type="number" class="form-control" name="inputKodePaymentChannel" id="inputKodePaymentChannel" placeholder="ex. 302, 405">
               </div>
               <div class="form-group">
                  <label for="">Nama Payment Channel</label>
                  <input type="text" class="form-control" name="inputNamaPaymentChannel" id="inputNamaPaymentChannel" placeholder="ex. LinkAja, BNI VA">
               </div>
               <div class="form-group">
                  <label for="">Logo Payment Channel</label>
                  <input type="file" class="form-control" name="inputLogoPaymentChannel" id="inputLogoPaymentChannel" accept="image/png, image/jpeg, image/gif">
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

<div class="modal fade" id="modalEditPaymentChannel">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header bg-primary" style="background-image: linear-gradient(to right bottom, #00C6FF, #0072FF)">
            <h4 class="modal-title">Edit Payment Channel</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span></button>
         </div>
         <form id="formEditPaymentChannel" type="post" enctype="multipart/form-data">
            <div class="modal-body">
               <div class="form-group">
                  <label for="">Kode Payment Channel</label>
                  <input type="number" class="form-control" name="editKodePaymentChannel" id="editKodePaymentChannel" placeholder="ex. 302, 405" readonly>
               </div>
               <div class="form-group">
                  <label for="">Nama Payment Channel</label>
                  <input type="text" class="form-control" name="editNamaPaymentChannel" id="editNamaPaymentChannel" placeholder="ex. LinkAja, BNI VA">
               </div>
               <div class="form-group">
                  <label for="">Logo Payment Channel</label>
                  <input type="file" class="form-control" name="editLogoPaymentChannel" id="editLogoPaymentChannel" accept="image/png, image/jpeg, image/gif">
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