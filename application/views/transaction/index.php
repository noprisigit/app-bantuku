<section class="content">
   <div class="card">
      <div class="card-body">
         <div class="table-responsive">
            <table id="transaction" class="table table-bordered table-striped" width="100%">
               <thead>
                  <tr>
                     <th class="text-center" style="vertical-align: middle; text-align: center;">#</th>
                     <th class="text-center" style="vertical-align: middle; text-align: center;">Nomor Invoice</th>
                     <th class="text-center" style="vertical-align: middle; text-align: center;">Nomor Pesanan</th>
                     <th class="text-center" style="vertical-align: middle; text-align: center;">Nama Produk</th>
                     <th class="text-center" style="vertical-align: middle; text-align: center;">Nama Toko</th>
                     <th class="text-center" style="vertical-align: middle; text-align: center;">Nama Customer</th>
                     <th class="text-center" style="vertical-align: middle; text-align: center;">Jumlah</th>
                     <th class="text-center" style="vertical-align: middle; text-align: center;">Total Bayar</th>
                     <th class="text-center" style="vertical-align: middle; text-align: center;">Status Pesanan</th>
                     <th class="text-center" style="vertical-align: middle; text-align: center;">Actions</th>
                  </tr>
               </thead>
               <tbody></tbody>
            </table>
         </div>
      </div>
   </div>
</section>

<div class="modal fade" id="modalDetailOrder">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header bg-primary" style="background-image: linear-gradient(to right bottom, #00C6FF, #0072FF)">
            <h4 class="modal-title">Detail Pesanan</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body">
            <div class="card mb-0">
               <div class="card-body">
                  <h5 class="text-bold" id="detailInvoice">Invoice: Loading...</h5>
                  <div class="row mt-3">
                     <div class="col-md-6">                                                            
                        <table class="table">
                           <tr>
                              <td class="text-bold" width="40%">Nomor Pesanan</td>
                              <td id="detailOrderNumber">: Loading...</td>
                           </tr>
                           <tr>
                              <td class="text-bold">Nama Customer</td>
                              <td id="detailCustomerName">: Loading...</td>
                           </tr>
                           <tr>
                              <td class="text-bold">Nama Toko</td>
                              <td id="detailPartnerName">: Loading...</td>
                           </tr>
                           <tr>
                              <td class="text-bold">Nama Produk</td>
                              <td id="detailProductName">: Loading...</td>
                           </tr>
                        </table>
                     </div>
                     <div class="col-md-6">
                        <table class="table">
                           <tr>
                              <td class="text-bold" width="40%">Jumlah Pesanan</td>
                              <td id="detailJumlahPesanan">: Loading...</td>
                           </tr>
                           <tr>
                              <td class="text-bold">Total Bayar</td>
                              <td id="detailTotalBayar">: Loading...</td>
                           </tr>
                           <tr>
                              <td class="text-bold">Tanggal Pesan</td>
                              <td id="detailOrderDate">: Loading...</td>
                           </tr>
                           <tr>
                              <td class="text-bold">Status Pesanan</td>
                              <td id="detailOrderStatus">: Loading...</td>
                           </tr>
                        </table>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        <table class="table">
                           <tr>
                              <td class="text-bold" width="20%">Alamat Pengiriman</td>
                              <td id="detailShippingAddress">: Loading...</td>
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
   </div>
</div>
