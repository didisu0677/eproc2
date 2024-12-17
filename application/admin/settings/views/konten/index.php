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
				<div class="show-panel sticky-top">
					<div class="card">
						<div class="card-header">
							<?php echo lang('konten'); ?>
						</div>
						<div class="card-body dropdown-menu">
							<a class="dropdown-item<?php if($page == 'kebijakan') echo ' active'; ?>" href="<?php echo base_url('settings/konten'); ?>"><?php echo lang('kebijakan');?></a>
							<a class="dropdown-item<?php if($page == 'disclaimer') echo ' active'; ?>" href="<?php echo base_url('settings/konten?p=disclaimer'); ?>"><?php echo lang('disclaimer'); ?></a>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-9">
			<?php 
			form_open(base_url('settings/konten/save'),'post','form','data-submit="ajax" data-callback="reload"');
				col_init(0,12);
				input('hidden','key','key','',$page);
				textarea(lang('konten'),'konten','required',setting($page),'data-editor');
				form_button(lang('perbaharui'),false);
			form_close();
			?>
			</div>
		</div>
	</div>
</div>
<script src="<?php echo base_url('assets/plugins/ckeditor/ckeditor.js'); ?>"></script>