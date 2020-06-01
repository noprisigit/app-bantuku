<section class="content">
   <div class="container-fluid">
      <div class="row">
         <div class="col-12">
            <div class="card">
               <div class="card-body">
                  <div class="row">
                     <div class="col-md-7">
                        <label for="">Nomor Invoice</label>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-7">
                        <div class="form-group">
                           <input type="number" id="inputInvoice" class="form-control" placeholder="ex. 893472947312">
                        </div>   
                     </div>
                     <div class="col-md-5">
                        <div class="form-group">
                           <button type="button" class="btn btn-primary btn-block btn-cari-invoice">Cari Invoice</button>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <!-- Image loader -->
            <div id="loader" style="display: none;">
               <img src="<?= base_url('assets/dist/img/waiting.gif'); ?>" class="d-flex mx-auto">
            </div>
            <!-- Image loader -->
            <div class="callout callout-danger" id="invoiceErrorDisplay" style="display: none;">
              <h5><i class="fas fa-info"></i> Catatan:</h5>
              Nomor Invoice Tidak Ditemukan.
            </div>
            <!-- Main content -->
            <div class="invoice p-3 mb-3" id="invoiceDisplay" style="display: none;">
               <!-- title row -->
               <div class="row">
                  <div class="col-12">
                     <h4>
                        <img src="<?= base_url('assets/dist/img/logo.png') ?>" alt="Logo-Bantuku" width="50"> Bantuku.
                        <small class="float-right">Nomor Invoice: <strong id="invoiceNumber"></strong></small>
                     </h4>
                  </div>
                  <!-- /.col -->
               </div>
               <!-- info row -->
               <div class="row invoice-info">
                  <div class="col-sm-6 invoice-col">
                     Dari
                     <address>
                        <strong>Bantuku.</strong><br />
                        
                        Phone: (804) 123-5432<br />
                        Email: support@bantuku2020.babelprov.go.id
                     </address>
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-6 invoice-col">
                     Kepada
                     <address>
                        <span><strong id="customerName">Customer Name</strong></span><br />
                        <span id="customerAddress">asdsadsadkl</span> <br />
                        <span id="customerPhone">Phone: 012932932</span> <br />
                        <span id="customerEmail">Email: john@gmail.com</span>
                     </address>
                  </div>
                  <!-- /.col -->
                  <!-- <div class="col-sm-4 invoice-col">
                     <b>Invoice #007612</b><br />
                     <br />
                     <b>Order ID:</b> 4F3S8J<br />
                     <b>Payment Due:</b> 2/22/2014<br />
                     <b>Account:</b> 968-34567
                  </div> -->
                  <!-- /.col -->
               </div>
               <!-- /.row -->

               <!-- Table row -->
               <div class="row">
                  <div class="col-12 table-responsive">
                     <table class="table table-striped">
                        <thead>
                           <tr>
                              <th>Nama Barang</th>
                              <th class="text-center">Jumlah</th>
                              <th class="text-center">Berat</th>
                              <th class="text-center">Harga Barang</th>
                              <th class="text-center">Subtotal</th>
                           </tr>
                        </thead>
                        <tbody id="detailOrder">
                           
                        </tbody>
                     </table>
                  </div>
                  <!-- /.col -->
               </div>
               <!-- /.row -->

               <div class="row">
                  <!-- accepted payments column -->
                  <div class="col-6">
                     <!-- <p class="lead">Payment Methods:</p>
                     <img src="../../dist/img/credit/visa.png" alt="Visa" />
                     <img src="../../dist/img/credit/mastercard.png" alt="Mastercard" />
                     <img src="../../dist/img/credit/american-express.png" alt="American Express" />
                     <img src="../../dist/img/credit/paypal2.png" alt="Paypal" />

                     <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                        Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya handango imeem plugg dopplr jibjab, movity jajah plickers sifteo edmodo ifttt zimbra.
                     </p> -->
                  </div>
                  <!-- /.col -->
                  <div class="col-6">
                     <p class="lead" id="tglPesan"></p>

                     <div class="table-responsive">
                        <table class="table">
                           <tr>
                              <th style="width: 50%;">Subtotal:</th>
                              <td id="subTotal">$250.30</td>
                           </tr>
                           <tr>
                              <th>Tax (10%)</th>
                              <td id="tax">$10.34</td>
                           </tr>
                           <!-- <tr>
                              <th>Shipping:</th>
                              <td>$5.80</td>
                           </tr> -->
                           <tr>
                              <th>Total:</th>
                              <td id="grandTotal">$265.24</td>
                           </tr>
                        </table>
                     </div>
                  </div>
                  <!-- /.col -->
               </div>
               <!-- /.row -->

               <!-- this row will not appear when printing -->
               <div class="row no-print">
                  <div class="col-12">
                     <a target="_blank" class="btn btn-default" id="printInvoice"><i class="fas fa-print"></i> Print</a>
                     <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;"><i class="fas fa-download"></i> Generate PDF</button>
                  </div>
               </div>
            </div>
            <!-- /.invoice -->
         </div>
         <!-- /.col -->
      </div>
      <!-- /.row -->
   </div>
   <!-- /.container-fluid -->
</section>