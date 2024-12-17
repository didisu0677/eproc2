<link rel="stylesheet" href="<?php echo base_url('assets/plugins/breakingnews/breakingnews.css'); ?>">
<div class="content-body body-home bg-grey">
	<div class="position-relative">
		<div class="offset-header"></div>
		<div class="main-container pt-3">
			<div class="row">
				<div class="col-sm-4 mb-3 mb-sm-4">
					<div class="card">
						<div class="card-body">
							<div class="dashboard-avatar">
								<img src="<?php echo user('foto'); ?>" class="rounded-circle" alt="avatar">
							</div>
							<div class="dashboard-content">
								<div class="dashboard-main-text single-line"><?php echo user('nama'); ?></div>
								<div class="single-line mb-1 dashboard-secondary-text"><?php echo user('email'); ?></div>
								<a href="<?php echo base_url('account/profile'); ?>" class="d-inline-block mr-3 mb-1"><?php echo lang('profil'); ?></a>
								<a href="<?php echo base_url('account/changepwd'); ?>" class="d-inline-block"><?php echo lang('ubah_kata_sandi'); ?></a>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-4 mb-3 mb-sm-4">
					<div class="card">
						<div class="card-body">
							<div class="dashboard-avatar">
								<div class="icon-avatar"><i class="fa-<?php echo $browser; ?>"></i></div>
							</div>
							<div class="dashboard-content">
								<div class="single-line dashboard-secondary-text"><?php echo $agent; ?></div>
								<div class="dashboard-main-text single-line mb-1"><?php echo $ip; ?></div>
								<span class="d-inline-block mb-1">&nbsp;</span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-4 mb-3 mb-sm-4">
					<div class="card">
						<div class="card-body">
							<div class="dashboard-avatar">
								<div class="icon-avatar"><i class="fa-shopping-cart"></i></div>
							</div>
							<div class="dashboard-content">
								<div class="row">
									<div class="col-6">
										<div class="single-line dashboard-secondary-text"><?php echo lang('pengumuman_lelang'); ?></div>
										<div class="dashboard-main-text single-line mb-1"><?php echo $pengadaan_baru; ?></div>
										<a href="<?php echo base_url('pengadaan_v/daftar_pengadaan_v'); ?>" class="d-inline-block mr-3 mb-1"><?php echo lang('lihat'); ?></a>
									</div>
									<div class="col-6">
										<div class="single-line dashboard-secondary-text"><?php echo lang('undangan_langsung'); ?></div>
										<div class="dashboard-main-text single-line mb-1"><?php echo $undangan_pengadaan; ?></div>
										<a href="<?php echo base_url('pengadaan_v/undangan_pengadaan'); ?>" class="d-inline-block mr-3 mb-1"><?php echo lang('lihat'); ?></a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php if(count($pengumuman) > 0) { ?>
				<div class="col-sm-12 mb-3 mb-sm-4 sticky-top">
					<div class="breakingNews" id="bn7">
						<div class="bn-title"><h2>Informasi</h2><span></span></div>
						<ul>
							<?php foreach($pengumuman as $p) { ?>
							<li><span><?php echo $p['nama']; ?></span> - <?php echo $p['pengumuman']; ?></li>
							<?php } ?>
						</ul>
						<div class="bn-navi">
							<span></span>
							<span></span>
						</div>
					</div>
				</div>
				<?php } ?>
				<div class="col-sm-12 mb-3 mb-sm-4">
					<div class="card">
						<div class="card-header pt-2 pb-2 pr-3 pl-3">
							<?php echo lang('pengumuman_lelang'); ?>
						</div>
						<div class="card-body p-0">
							<div class="table-responsive">
								<table class="table table-app table-striped table-hover">
									<thead>
										<tr>
											<th><?php echo lang('nomor_pengadaan'); ?></th>
											<th><?php echo lang('nama_pengadaan'); ?></th>
											<th><?php echo lang('tanggal_pengadaan'); ?></th>
											<th width="50">&nbsp;</th>
										</tr>
									</thead>
									<tbody>
										<?php if(count($pengadaan_baru_list) > 0) { foreach($pengadaan_baru_list as $p) { ?>
										<tr>
											<td><?php echo $p->nomor_pengadaan; ?></td>
											<td><?php echo $p->nama_pengadaan; ?></td>
											<td><?php echo date_lang($p->tanggal_pengadaan) ?></td>
											<td><a href="<?php echo base_url('pengadaan_v/daftar_pengadaan_v/detail/'.encode_id([$p->id,rand()])); ?>" class="btn btn-sm btn-info btn-icon-only"><i class="fa-search"></i></a>
										</tr>
										<?php }} else { ?>
										<tr>
											<td colspan="5"><?php echo lang('tidak_ada_data'); ?></td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
						<div class="card-footer text-center border-top-0 bg-white">
							<a href="<?php echo base_url('pengadaan_v/daftar_pengadaan_v'); ?>"><?php echo lang('lihat_semua'); ?></a>
						</div>
					</div>
				</div>
				<div class="col-sm-12 mb-3 mb-sm-4">
					<div class="card">
						<div class="card-header pt-2 pb-2 pr-3 pl-3">
							<?php echo lang('undangan_langsung'); ?>
						</div>
						<div class="card-body p-0">
							<div class="table-responsive">
								<table class="table table-app table-striped table-hover">
									<thead>
										<tr>
											<th><?php echo lang('nomor_pengadaan'); ?></th>
											<th><?php echo lang('nama_pengadaan'); ?></th>
											<th><?php echo lang('tanggal_pengadaan'); ?></th>
											<th width="50">&nbsp;</th>
										</tr>
									</thead>
									<tbody>
										<?php if(count($undangan_pengadaan_list) > 0) { foreach($undangan_pengadaan_list as $p) { ?>
										<tr>
											<td><?php echo $p->nomor_pengadaan; ?></td>
											<td><?php echo $p->nama_pengadaan; ?></td>
											<td><?php echo date_lang($p->tanggal_pengadaan) ?></td>
											<td><a href="<?php echo base_url('pengadaan_v/undangan_pengadaan/detail/'.encode_id([$p->id,rand()])); ?>" class="btn btn-sm btn-info btn-icon-only"><i class="fa-search"></i></a>
										</tr>
										<?php }} else { ?>
										<tr>
											<td colspan="5"><?php echo lang('tidak_ada_data'); ?></td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
						<div class="card-footer text-center border-top-0 bg-white">
							<a href="<?php echo base_url('pengadaan_v/undangan_pengadaan'); ?>"><?php echo lang('lihat_semua'); ?></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div> 
<script src="<?php echo base_url('assets/plugins/breakingnews/breakingnews.js'); ?>"></script>
<script>
	$("#bn7").breakingNews({
		effect		:"slide-v",
		autoplay	:true,
		timer		:5000,
	});
</script>