        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <img src="<?= base_url('assets/dist/img/pemprov.png') ?>" class="img-fluid" width="30px" alt="Logo Pemprov">
            </div>
            <strong>Copyright &copy; <span><b>Dinas Komunikasi dan Informasi Bangka Belitung.</b></span> All rights reserved.
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script type="text/javascript" src="<?= base_url('assets') ?>/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= base_url('assets') ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables -->
    <script src="<?= base_url('assets') ?>/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= base_url('assets') ?>/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="<?= base_url('assets') ?>/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="<?= base_url('assets') ?>/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="<?= base_url('assets') ?>/plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- Toastr -->
    <script src="<?= base_url('assets') ?>/plugins/toastr/toastr.min.js"></script>
    <!-- Select2 -->
    <script src="<?= base_url('assets') ?>/plugins/select2/js/select2.full.min.js"></script>
    <!-- Sript -->
    <script src="<?= base_url('assets') ?>/dist/js/script.js"></script>
    <?php foreach($js as $item) : ?>
        <script src="<?= base_url($item) ?>"></script>
    <?php endforeach; ?>
    <!-- AdminLTE App -->
    <script src="<?= base_url('assets') ?>/dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="<?= base_url('assets') ?>/dist/js/demo.js"></script>
    <script type="text/javascript">
        $('.custom-file-input').on('change', function () {
            let filename = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass('selected').html(filename);
        });

        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })

        $('.province').on('change', function() {
            const ProvinceID = $(this).val();

            $('.district').empty();
            $.ajax({
                url: "<?= base_url('partner/district_get') ?>",
                type: "post",
                data: { ProvinceID: ProvinceID },
                dataType: "json",
                success: function(res) {
                    $('.district').append('<option selected="selected" disabled>Kabupaten</option>');
                    for (var i = 0; i < res.length; i++) {
                        $('.district').append('<option value="' + res[i]['DistrictName'] + '">' + res[i]['DistrictName'] + '</option>');
                    }
                }
            });
        })
    </script>
</body>

</html>