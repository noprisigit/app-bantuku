

            <!-- Main content -->
            <section class="content">

            <div class="card">
                    <div class="card-header">
                        <button type="button" data-toggle="modal" data-target="#modal-add-slider" class="btn btn-primary" style="background-image: linear-gradient(to right bottom, #00C6FF, #0072FF)"><i class="fas fa-plus-square"></i> Tambah Slider</button>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="slider" class="table table-bordered table-striped" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Description</th>
                                    <th class="text-center">Start Date</th>
                                    <th class="text-center">End Date</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Description</th>
                                    <th class="text-center">Start Date</th>
                                    <th class="text-center">Start Date</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

            </section>
            <!-- /.content -->

            <div class="modal fade" id="modal-add-slider">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary" style="background-image: linear-gradient(to right bottom, #00C6FF, #0072FF)">
                            <h4 class="modal-title">Tambah Mitra</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                        </div>
                        <form id="form-save-slider" method="post" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="slidername">Slider Name</label>
                                            <input type="text" class="form-control" name="name" id="slider-name" placeholder="Slider Name">
                                        </div>
                                        <div class="form-group">
                                            <label for="sliderdescription">Slider Description</label>
                                            <input type="text" class="form-control" name="description" id="slider-description" placeholder="Slider Description">
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="startdate">Start Date</label>
                                                    <input type="date" class="form-control" name="start_date" id="slider-start">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="enddate">End Date</label>
                                                    <input type="date" class="form-control" name="end_date" id="slider-end">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="icon">Slider Picture</label>
                                            <input type="file" class="form-control" name="picture" id="slider-picture" accept="image/png, image/jpeg, image/gif">
                                            <!-- <div class="custom-file">
                                                <label class="custom-file-label" for="customFile">Choose file</label>
                                            </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" id="btn-save-slider" class="btn btn-primary">Tambah</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
        