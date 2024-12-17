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
		<?php
		form_open(base_url('pengadaan/impor_sap/proses'),'post','form-import','data-submit="ajax" data-callback="reload"');
			col_init(3,9);
			select2(lang('tipe_dokumen'),'tipe','required',['PR','BP']);
			fileupload('File (xls, xlsx, csv)','fileimport','required','data-accept="xls|xlsx|csv"');
			form_button(lang('impor'),lang('batal'));
		form_close();
		?>
	</div>
</div>
