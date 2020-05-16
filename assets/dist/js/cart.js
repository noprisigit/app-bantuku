$(document).ready(function() {
   var table_cart = $('#cart').DataTable({
      "processing": true, 
      "serverSide": true, 
      "order": [], 
      "ajax": {
         "url": "cart/show-list-cart",
         "type": "POST"
      },
      "columnDefs": [
         { 
            "targets": [ 0 ], 
            "orderable": false, 
            "className": "text-center"
         },
         {
            "targets": [5,6,7],
            "className": "text-center"
         }
      ],
   });
});