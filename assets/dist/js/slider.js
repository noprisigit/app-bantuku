$(document).ready(function() {
   var table_slider = $('#slider').DataTable({
      "processing": true, 
      "serverSide": true, 
      "order": [], 
      "ajax": {
         "url": "slider/show-list-slider",
         "type": "POST"
      },
      "columnDefs": [
         { 
            "targets": [ 0 ], 
            "orderable": false, 
            "className": "text-center"
         },
         {
            "targets": [3,4,5],
            "className": "text-center"
         }
      ],
   });

   $('#form-save-slider').on('submit', function(e) {
      const name = $('#slider-name').val();
      const description = $('#slider-description').val();
      const start_date = $('#slider-start').val();
      const end_date = $('#slider-end').val();
      const picture = $('#slider-picture').val();

      const checkStartDate = new Date($('#slider-start').val());
      const checkEndDate = new Date($('#slider-end').val());
      const currentDate = new Date();

      if (name == "" || description == "" || start_date == "" || end_date == "" || picture == "") {
          e.preventDefault();
          toastr.error('Please fill all the field');
      } else {
         if (checkStartDate < currentDate) {
            e.preventDefault();
            toastr.error('Tanggal mulai sudah lewat');
         } else if (checkStartDate > checkEndDate) {
            e.preventDefault();
            toastr.error('Tanggal mulai tidak boleh lebih besar dari tanggal berakhir');
         } else {
            $.ajax({
               url: 'slider/slider-save',
               method: 'post',
               data: new FormData(this),
               dataType: 'json',
               contentType: false,
               cache: false,
               processData: false,
               success: function(res) {
                  if (res.status == false) {
                     toastr.error(res.msg);
                  } else {
                     $('[name="name"]').val("");
                     $('[name="description"]').val("");
                     $('[name="start_date"]').val("");
                     $('[name="end_date"]').val("");
                     $('[name="picture"]').val("");

                     $('#modal-add-slider').modal('hide');

                     Swal.fire({
                        title: "Slider",
                        text: "Slider berhasil ditambahkan",
                        icon: 'success'
                     });
                     table_slider.ajax.reload();
                  }
               }
            });
            return false;
         }
      }
  });

  $(document).on('click', '.btn-detail-slider', function() {
      $('#modal-detail-slider').modal('show');

      const name = $(this).data('name');
      const description = $(this).data('description');
      const start = $(this).data('start');
      const end = $(this).data('end');
      const picture = $(this).data('picture');

      $('#img-slider').attr('src', 'assets/dist/img/sliders/' + picture);
      $('#det-slider-name').html(': ' + name);
      $('#det-slider-description').html(': ' + description);
      $('#det-slider-start').html(': ' + start);
      $('#det-slider-end').html(': ' + end);
  });

  $(document).on('click', '.btn-edit-slider', function() {
      $('#modal-edit-slider').modal('show');

      const id = $(this).data('id');
      const name = $(this).data('name');
      const description = $(this).data('description');
      const start = $(this).data('start');
      const end = $(this).data('end');

      $('#slider_id').val(id);
      $('#edit-slider-name').val(name);
      $('#edit-slider-description').val(description);
      $('#edit-slider-start').val(start);
      $('#edit-slider-end').val(end);
  });

  $('#form-edit-slider').on('submit', function(e) {
      const name = $('#edit-slider-name').val();
      const description = $('#edit-slider-description').val();
      const start_date = $('#edit-slider-start').val();
      const end_date = $('#edit-slider-end').val();

      const checkStartDate = new Date($('#edit-slider-start').val());
      const checkEndDate = new Date($('#edit-slider-end').val());
      const currentDate = new Date();

      if (name == "" || description == "" || start_date == "" || end_date == "") {
         e.preventDefault();
         toastr.error('Please fill all the field');
      } else {
         if (checkStartDate < currentDate) {
            e.preventDefault();
            toastr.error('Tanggal mulai sudah lewat');
         } else if (checkStartDate > checkEndDate) {
            e.preventDefault();
            toastr.error('Tanggal mulai tidak boleh lebih besar dari tanggal berakhir');
         } else {
            $.ajax({
               url: 'slider/slider-edit',
               method: 'post',
               data: new FormData(this),
               dataType: 'json',
               contentType: false,
               cache: false,
               processData: false,
               success: function(res) {
                  if(res.status == false) {
                     toastr.error(res.msg);
                  } else {
                     $('[name="edit_slider_name"]').val("");
                     $('[name="edit_slider_description"]').val("");
                     $('[name="edit_slider_start_date"]').val("");
                     $('[name="edit_slider_end_date"]').val("");
                     $('[name="edit_slider_picture"]').val("");

                     $('#modal-edit-slider').modal('hide');

                     Swal.fire({
                        title: "Slider",
                        text: "Slider berhasil diperbaharui",
                        icon: 'success'
                     });
                     table_slider.ajax.reload();
                  }
               }
            });
            return false;
         }
      }
  });

  $(document).on('click', '.btn-delete-slider', function(e){
      e.preventDefault();
      const SliderID = $(this).data('id');
      Swal.fire({
         title: 'Are you sure?',
         text: "Slider ini akan dihapus",
         icon: 'warning',
         showCancelButton: true,
         confirmButtonColor: '#3085d6',
         cancelButtonColor: '#d33',
         confirmButtonText: 'Hapus'
      }).then((result) => {
         if (result.value) {
            $.ajax({
               type: "POST",
               url: "slider/slider-delete",
               data: { SliderID : SliderID },
               success: function () {
                     Swal.fire({
                        title: "Slider",
                        text: "Slider berhasil dihapus",
                        icon: 'success'
                     });
                     table_slider.ajax.reload();
               }
            });
         }
      })
   });
});