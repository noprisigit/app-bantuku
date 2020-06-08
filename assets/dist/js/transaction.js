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
            "targets": [5,6,8,9,10],
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
});