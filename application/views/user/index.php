

            <!-- Main content -->
            <section class="content">

            <div class="card">
                    <div class="card-header">
                        <button type="button" data-toggle="modal" data-target="#modal-add-account" class="btn btn-primary" style="background-image: linear-gradient(to right bottom, #00C6FF, #0072FF)"><i class="fas fa-plus-square"></i> Tambah Akun</button>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="users" class="table table-bordered table-striped nowrap" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Username</th>
                                        <th class="text-center">Status Access</th>
                                        <th class="text-center">Status Account</th>
                                        <th class="text-center">Last Login</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Username</th>
                                        <th class="text-center">Status Access</th>
                                        <th class="text-center">Status Account</th>
                                        <th class="text-center">Last Login</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

            </section>
            <!-- /.content -->

            <div class="modal fade" id="modal-change-password">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-primary" style="background-image: linear-gradient(to right bottom, #00C6FF, #0072FF)">
                            <h4 class="modal-title">Ubah Password</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                        </div>
                        <form id="form-ubah-password" method="post">
                            <div class="modal-body">
                                <div class="card">
                                    <div class="card-body">
                                        <input type="hidden" name="admin_id" id="admin_id">
                                        <div class="form-group">
                                            <label for="">Password Baru</label>
                                            <div class="input-group">
                                                <input type="password" name="password_baru" id="password_baru" class="form-control border-right-0" placeholder="Password Baru">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="toggle-password">
                                                        <i class="fas fa-eye" aria-hidden="true"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Konfirmasi Password</label>
                                            <div class="input-group">
                                                <input type="password" name="confirm_password" id="confirm_password_baru" class="form-control border-right-0" placeholder="Konfirmasi Password">
                                                <span class="input-group-append">
                                                    <span class="input-group-text" id="toggle-confirm-password">
                                                        <i class="fas fa-eye" aria-hidden="true"></i>
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Ubah Password</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->

            <div class="modal fade" id="modal-add-account">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-primary" style="background-image: linear-gradient(to right bottom, #00C6FF, #0072FF)">
                            <h4 class="modal-title">Tambah Akun</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                        </div>
                        <form id="form-save-account" method="post">
                            <div class="modal-body">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="">Nama</label>
                                            <input type="text" name="name" id="admin_name" class="form-control" placeholder="Nama">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Username</label>
                                            <input type="text" name="username" id="admin_username" class="form-control" placeholder="Username">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Password</label>
                                            <div class="input-group">
                                                <input type="password" name="password" id="admin_password" class="form-control border-right-0" placeholder="Password">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="toggle-password">
                                                        <i class="fas fa-eye" aria-hidden="true"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Konfirmasi Password</label>
                                            <div class="input-group">
                                                <input type="password" name="confirm_password" id="admin_confirm_password" class="form-control border-right-0" placeholder="Konfirmasi Password">
                                                <span class="input-group-append">
                                                    <span class="input-group-text" id="toggle-confirm-password">
                                                        <i class="fas fa-eye" aria-hidden="true"></i>
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Status Akses</label>
                                            <select name="status_akses" id="admin_status_akses" class="form-control">
                                                <option selected disabled>Status Akses</option>
                                                <option value="1">Super Administrator</option>
                                                <option value="2">Administrator</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
        