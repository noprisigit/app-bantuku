var table;
$(document).ready(function () {   
    table = $('#category').DataTable({ 
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "ajax": {
            "url": "category/show-list-category",
            "type": "POST"
        },
        "columnDefs": [
            { 
                "targets": [ 0 ], 
                "orderable": false, 
                "className": "text-center"
            },
            {
                "targets": [4,5],
                "className": "text-center"
            }
        ],
    });

    const flashData = $('.flash-data').data('flashdata');
    const title = $('.flash-data').data('title');

    if (flashData) {
        Swal.fire({
            title: title,
            text: flashData,
            icon: 'success'
        });
    }

    //delete category
    $(document).on('click', '.btn-delete-category', function(e){
        e.preventDefault();
        const CategoryID = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "This category will be deleted",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Delete!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: "category/delete-category",
                    data: { category_id : CategoryID },
                    success: function (res) {
                        Swal.fire({
                            title: "Category",
                            text: "Category has been deleted",
                            icon: 'success'
                        });
                        table.ajax.reload();
                    }
                });
            }
        })
    });
});



