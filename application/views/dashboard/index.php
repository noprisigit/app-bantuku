

            <!-- Main content -->
            <section class="content">
					<div class="row">
						<div class="col-md-3 col-sm-6 col-12">
							<div class="info-box">
								<span class="info-box-icon bg-info"><i class="fas fa-store-alt"></i></span>

								<div class="info-box-content">
									<span class="info-box-text">Jumlah Merchant</span>
									<span class="info-box-number" id="countMerchant"></span>
								</div>
								<!-- /.info-box-content -->
							</div>
							<!-- /.info-box -->
						</div>
						<!-- /.col -->
						<div class="col-md-3 col-sm-6 col-12">
							<div class="info-box">
								<span class="info-box-icon bg-success"><i class="fas fa-users"></i></span>

								<div class="info-box-content">
									<span class="info-box-text">Jumlah Customer</span>
									<span class="info-box-number" id="countCustomer"></span>
								</div>
								<!-- /.info-box-content -->
							</div>
							<!-- /.info-box -->
						</div>
						<!-- /.col -->
						<div class="col-md-3 col-sm-6 col-12">
							<div class="info-box">
								<span class="info-box-icon bg-warning"><i class="fas fa-clipboard-list"></i></span>

								<div class="info-box-content">
									<span class="info-box-text">Jumlah Kategori</span>
									<span class="info-box-number" id="countCategory"></span>
								</div>
								<!-- /.info-box-content -->
							</div>
							<!-- /.info-box -->
						</div>
						<!-- /.col -->
						<div class="col-md-3 col-sm-6 col-12">
							<div class="info-box">
								<span class="info-box-icon bg-primary"><i class="fas fa-dollar-sign"></i></span>

								<div class="info-box-content">
									<span class="info-box-text">Total Pendapatan</span>
									<span class="info-box-number" id="jumlahPendapatan"></span>
								</div>
								<!-- /.info-box-content -->
							</div>
							<!-- /.info-box -->
						</div>
						<!-- /.col -->
					</row>

					<div class="container-fluid">
						<div class="row">
							<div class="col-md-6 col-sm-6 col-12">
								<div class="card card-primary">
									<div class="card-header">
										<h3 class="card-title">5 Produk Yang Paling Disukai</h3>
									</div>
									<div class="card-body">
										<table class="table">
											<thead>
												<tr class="text-center">
													<th>#</th>
													<th>Nama Produk</th>
													<th>Nama Toko</th>
													<th>Yang Suka</th>
												</tr>
											</thead>
											<tbody id="produk-disukai">
												
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="col-md-6 col-sm-6 col-12">
								<div class="card card-primary">
									<div class="card-header">
										<h3 class="card-title">5 Toko Yang Paling Disukai</h3>
									</div>
									<div class="card-body">
										<table class="table">
											<thead>
												<tr class="text-center">
													<th>#</th>
													<th>Nama Toko</th>
													<th>Nama Pemilik</th>
													<th>Yang Suka</th>
												</tr>
											</thead>
											<tbody id="toko-disukai">
												
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<!-- <div class="col-md-6">
								<div class="card card-success">
									<div class="card-header">
										<h3 class="card-title">Grafik Jumlah Customer Bulan Ini</h3>

										<div class="card-tools">
											<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
											</button>
											<button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
										</div>
									</div>
									<div class="card-body">
										<div class="chart">
											<div class="chartjs-size-monitor">
													<div class="chartjs-size-monitor-expand">
														<div class=""></div>
													</div>
													<div class="chartjs-size-monitor-shrink">
														<div class=""></div>
													</div>
											</div>
											<canvas id="customerCurrentMonth" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 616px;" width="924" height="375" class="chartjs-render-monitor"></canvas>
										</div>
									</div>
								</div>
							</div> -->
							<!-- /.col (RIGHT) -->
							<div class="col-md-6">
								<!-- BAR CHART -->
								<div class="card card-success">
									<div class="card-header">
										<h3 class="card-title">Grafik Jumlah Customer Tahun Ini</h3>

										<div class="card-tools">
											<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
											</button>
											<button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
										</div>
									</div>
									<div class="card-body">
										<div class="chart">
											<div class="chartjs-size-monitor">
													<div class="chartjs-size-monitor-expand">
														<div class=""></div>
													</div>
													<div class="chartjs-size-monitor-shrink">
														<div class=""></div>
													</div>
											</div>
											<canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 616px;" width="924" height="375" class="chartjs-render-monitor"></canvas>
										</div>
									</div>
									<!-- /.card-body -->
								</div>
								<!-- /.card -->
							</div>
							<!-- /.col (RIGHT) -->
						</div>
					</div>
            </section>
            <!-- /.content -->
        