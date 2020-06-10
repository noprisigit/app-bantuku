$(document).ready(function() {
   var table_transaction = $('#transaction').DataTable({
      "processing": true, 
      "serverSide": true, 
      "order": [], 
      "ajax": {
         "url": "transaction/show-list-transactions",
         "type": "POST"
      },
      "columnDefs": [
         { 
            "targets": [ 0 ], 
            "orderable": false, 
            "className": "text-center"
         },
         {
            "targets": [1],
            "width": "40px"
         },
         {
            "targets": [6],
            "width": "30px"
         },
         {
            "targets": [7],
            "width": "60px"
         },
         {
            "targets": [8],
            "className": "text-center"
         },
         {
            "targets": [9],
            "className": "text-center"
         }
      ],
   });

   setInterval(function() {
      table_transaction.ajax.reload();
   }, 300000);

   $(document).on('click', '.btnProsesOrder', function() {
      var invoice = $(this).data('invoice');
      $('.'+invoice).html('Loading...');
      
      $.ajax({
         url: "transaction/processOrder",
         type: "post",
         data: { invoice: invoice },
         success: function() {
            Swal.fire({
               title: "Pesanan",
               text: "Pesanan akan diproses",
               icon: 'success'
            });
            table_transaction.ajax.reload();
         }
      });
      return false;
   });

   $(document).on('click', '.btnKirimOrder', function() {
      var invoice = $(this).data('invoice');
      $('.'+invoice).html('Loading...');

      $.ajax({
         url: "transaction/sendOrder",
         type: "post",
         data: { invoice: invoice },
         success: function() {
            Swal.fire({
               title: "Pesanan",
               text: "Pesanan akan dikirim",
               icon: 'success'
            });
            table_transaction.ajax.reload();
         }
      });
      return false;
   });

   $(document).on('click', '.btnDetailOrder', function() {
      var orderNumber = $(this).data('order');
      var statusPesanan;
      $('#modalDetailOrder').modal('show');
      
      $.ajax({
         url: 'transaction/getDetailOrder',
         type: 'post',
         dataType: 'json',
         data: { orderNumber: orderNumber },
         success: function(data) {
            if (data.OrderStatus === "1") statusPesanan = '<span class="badge badge-danger">Pending</span>';
            if (data.OrderStatus === "2") statusPesanan = '<span class="badge badge-warning">Proses</span>';
            if (data.OrderStatus === "3") statusPesanan = '<span class="badge badge-info">Kirim</span>';
            if (data.OrderStatus === "4") statusPesanan = '<span class="badge badge-success">Selesai</span>';

            $('#detailInvoice').html("Invoice: " + data.Invoice);
            $('#detailOrderNumber').html(": " + orderNumber);
            $('#detailCustomerName').html(": " + data.CustomerName);
            $('#detailPartnerName').html(": " + data.CompanyName);
            $('#detailProductName').html(": " + data.ProductName);
            $('#detailJumlahPesanan').html(": " + data.OrderProductQuantity + " buah");
            $('#detailTotalBayar').html(": " + formatRupiah(data.OrderTotalPrice, "Rp "));
            $('#detailOrderDate').html(": " + changeDateFormat(data.OrderDate));
            $('#detailOrderStatus').html(": " + statusPesanan);
            $('#detailShippingAddress').html(": " + data.ShippingAddress);
         }
      });
      return false;
   });

   $(document).on('click', '.btnDeleteOrder', function() {
      var invNumber = $(this).data('invnumber');
      var invoice = $(this).data('invoice');
      var order = $(this).data('order');

      Swal.fire({
         title: 'Are you sure?',
         html: '<h5 class="text-bold">Invoice : '+invoice+'</h5><p class="mb-1">Nomor Pesanan : '+ order +'</p><p class="mb-0">Pesanan dengan invoice ini akan dihapus?</p>',
         icon: 'warning',
         showCancelButton: true,
         confirmButtonColor: '#3085d6',
         cancelButtonColor: '#d33',
         confirmButtonText: 'Hapus'
      }).then((result) => {
         if (result.value) {
            $.ajax({
               type: "POST",
               url: "transaction/deleteOrder",
               data: { invNumber : invNumber },
               success: function () {
                  Swal.fire({
                     title: "Invoice : " + invoice,
                     text: "Pesanan untuk invoice ini telah dihapus",
                     icon: 'success'
                  });
                  table_transaction.ajax.reload();
               }
            });
            return false;
         }
      })
   });

   function changeDateFormat(tanggal) {
      var parseTanggal1 = tanggal.split(" ");
      var parseTanggal2 = parseTanggal1[0].split("-");
      var time = parseTanggal1[1].split(":");
      var newDate = parseTanggal2[2] + "-" + parseTanggal2[1] + "-" + parseTanggal2[0] + " " + time[0] + ":" + time[1];
      return newDate;
   }

   function formatRupiah(angka, prefix){
      var number_string = angka.toString().replace(/[^,\d]/g, '').toString(),
      split   		= number_string.split(','),
      sisa     		= split[0].length % 3,
      rupiah     		= split[0].substr(0, sisa),
      ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
    
      // tambahkan titik jika yang di input sudah menjadi angka ribuan
      if(ribuan){
         separator = sisa ? '.' : '';
         rupiah += separator + ribuan.join('.');
      }
    
      rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
      return prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '');
   }
});