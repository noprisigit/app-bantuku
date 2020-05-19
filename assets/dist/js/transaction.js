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
            "targets": [4,5,6,7],
            "className": "text-center"
         }
      ],
   });

   setInterval(function() {
      table_transaction.ajax.reload();
   }, 300000);
});