$(document).ready(function() {
   var table_users = $('#customer').DataTable({
      "processing": true,
      "serverSide": true,
      "order": [],
      "ajax": {
         "url": "customer/show-list-customers",
         "type": "post"
      },
      "columnDefs": [
         {
            "targets": [ 0 ],
            "orderable": false,
            "className": "text-center"
         },
         {
            "targets": [ 5,6 ],
            "className": "text-center"
         }
      ]
   });

   $('#form-save-customer').submit(function(e) {
      var name = $('#customerName').val();
      var email = $('#customerEmail').val();
      var phone = $('#customerPhone').val();
      var pass = $('#customerPass').val();
      var confPass = $('#customerConfirmPass').val();
      var address = $('#customerAddress').val();

      if (name == "" || email == "" || phone == "" || pass == "" || confPass == "" || address == "") {
         e.preventDefault();
         toastr.error('Harap isi semua kolom');
      } else {
         if (pass != confPass) {
            e.preventDefault();
            toastr.error('Password dan konfirmasi password harus sama');
         } else {
            $.ajax({
               url: 'customer/customer_post',
               type: 'post',
               data: { name: name, email: email, phone: phone, pass: pass, address: address },
               dataType: 'json',
               success: function(res) {
                  if (res.status == false) {
                     e.preventDefault();
                     toastr.error(res.message)
                  } else {
                     $('[name="customerName"]').val("");
                     $('[name="customerEmail"]').val("");
                     $('[name="customerPhone"]').val("");
                     $('[name="customerPass"]').val("");
                     $('[name="customerConfirmPass"]').val("");
                     $('[name="customerAddress"]').val("");

                     $('#modal-add-customer').modal('hide');

                     Swal.fire({
                        title: "Customer",
                        text: res.message,
                        icon: 'success'
                    });
                  }
               }
            });
            return false;
         }
      }
   });

   $(document).on('click', '.btn-detail-customer', function() {
      var uniqID = $(this).data('uniqueid');
      var name = $(this).data('name');
      var email = $(this).data('email');
      var phone = $(this).data('phone');
      var address = $(this).data('address');
      var verified = $(this).data('verified');
      var date = $(this).data('date');

      $('#modal-detail-customer').modal('show');


      $('#detCustomerUniqID').html(": " + uniqID);
      $('#detCustomerName').html(": " + name);
      $('#detCustomerEmail').html(": " + email);
      $('#detCustomerPhone').html(": " + phone);
      $('#detCustomerAddress').html(": " + address);
      if (verified == 1) {
         $('#detCustomerEmailVerified').html(": " + '<span class="badge badge-success">Email Telah Diverifikasi</span>');
      } else {
         $('#detCustomerEmailVerified').html(": " + '<span class="badge badge-danger">Email Belum Diverifikasi</span>');
      }
      $('#detCustomerRegistrationDate').html(": " + date);
   });
});