<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body">
	<div class="main-container">
		<div class="row">
			<div class="col-sm-3 mb-2">
				<div class="show-panel sticky-top" id="accordion">
					<div class="card">
						<div class="card-header" data-toggle="collapse" data-target="#collapseKonten" aria-expanded="true" aria-controls="collapseKonten">
							<?php echo lang('konten'); ?>
						</div>
						<div id="collapseKonten" class="collapse show" aria-labelledby="headingKonten">
							<div class="card-body dropdown-menu">
								<a class="dropdown-item<?php if($page == 'tor') echo ' active'; ?>" href="<?php echo base_url('settings/templat_cetak'); ?>">TOR</a>
								<a class="dropdown-item<?php if($page == 'rks') echo ' active'; ?>" href="<?php echo base_url('settings/templat_cetak?p=rks'); ?>">RKS</a>
								<a class="dropdown-item<?php if($page == 'rks_aanwijzing') echo ' active'; ?>" href="<?php echo base_url('settings/templat_cetak?p=rks_aanwijzing'); ?>"><?php echo lang('rks_aanwijzing'); ?></a>
								<a class="dropdown-item<?php if($page == 'rks_klarifikasi') echo ' active'; ?>" href="<?php echo base_url('settings/templat_cetak?p=rks_klarifikasi'); ?>"><?php echo lang('rks_klarifikasi'); ?></a>
								<a class="dropdown-item<?php if($page == 'pengumuman_lelang') echo ' active'; ?>" href="<?php echo base_url('settings/templat_cetak?p=pengumuman_lelang'); ?>"><?php echo lang('pengumuman_lelang'); ?></a>
								<a class="dropdown-item<?php if($page == 'kontrak') echo ' active'; ?>" href="<?php echo base_url('settings/templat_cetak?p=kontrak'); ?>"><?php echo lang('kontrak'); ?></a>
							</div>
						</div>
					</div>
					<div class="card mt-2">
						<div class="card-header" data-toggle="collapse" data-target="#collapseRiwayat" aria-expanded="true" aria-controls="collapseRiwayat"><?php echo lang('riwayat_'.$page); ?></div>
						<div id="collapseRiwayat" class="collapse show" aria-labelledby="headingRiwayat">
							<div class="card-body dropdown-menu">
								<?php foreach($riwayat as $r) { ?>
								<div class="dropdown-item<?php if($periode == $r->periode) echo ' active'; ?>">
									<a href="<?php echo base_url('settings/templat_cetak?p='.$page.'&d='.$r->periode); ?>" class="text-secondary"><?php echo date_lang($r->periode); ?></a>
									<a href="javascript:;" data-id="<?php echo $r->id; ?>" class="btn-delete text-danger float-right"data-action="<?php echo base_url('settings/templat_cetak/delete'); ?>"><span class="fa-times"></span></a>
									<div class="clearfix"></div>
								</div>
								<?php } ?>
							</div>
						</div>
					</div>
					<div class="card mt-2">
						<div class="card-header" data-toggle="collapse" data-target="#collapseVariabel" aria-expanded="true" aria-controls="collapseVariabel"><?php echo lang('variabel'); ?></div>
						<div id="collapseVariabel" class="collapse show" aria-labelledby="headingVariabel">
							<div class="card-body p-2">
								<ul class="pl-3 mb-0">
									<li>---new_page---</li>
									<?php foreach($variable as $v) { ?>
									<li>{{<?php echo $v; ?>}}</li>
									<?php } ?>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-9">
			<?php 
			form_open(base_url('settings/templat_cetak/save'),'post','form','data-submit="ajax" data-callback="reload"');
				col_init(0,12);
				input('hidden','key','key','',$page);
				textarea(lang('konten'),'konten','required',$konten,'data-editor');
				?>
				<div class="form-group row">
					<div class="col-sm-5 mb-2 mb-sm-0">
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text"><?php echo lang('periode'); ?></span>
							</div>
							<input type="text" name="periode" id="periode" class="form-control dp dropup" autocomplete="off" value="<?php echo c_date($periode); ?>" />
						</div>
					</div>
					<div class="col-sm-7">
						<button type="submit" class="btn btn-info"><?php echo lang('perbaharui'); ?></button>
					</div>
				</div>
				<?php
			form_close();
			?>
			</div>
		</div>
	</div>
</div>
<script src="<?php echo base_url('assets/plugins/ckeditor/ckeditor.js'); ?>"></script>