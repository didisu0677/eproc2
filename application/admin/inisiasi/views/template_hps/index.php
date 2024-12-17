<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<div class="float-right">
			<?php echo access_button(); ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body">
	<?php
	table_open('',true,base_url('inisiasi/template_hps/data'),'tbl_template_hps');
		thead();
			tr();
				th('checkbox','text-center','width="30" data-content="id"');
				th(lang('klasifikasi'),'','data-content="klasifikasi"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<?php 
modal_open('modal-form','','modal-lg');
	modal_body();
		form_open(base_url('inisiasi/template_hps/save'),'post','form');
			col_init(3,9);
			input('hidden','id','id');
			input('hidden','klasifikasi','klasifikasi');
			select2(lang('klasifikasi'),'id_klasifikasi','required|unique',$klasifikasi,'id','klasifikasi','','data-target="klasifikasi"');
			label(lang('templat_header'));
			?>
			<div class="form-group row">
				<label class="col-form-label col-sm-3 required" for="label_uraian"><?php echo lang('uraian'); ?></label>
				<div class="col-sm-3">
					<input type="text" id="label_uraian" class="form-control" name="label_uraian" autocomplete="off" placeholder="<?php echo lang('label'); ?>" value="<?php echo lang('uraian'); ?>" data-validation="required|max-length:100">
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-3 required" for="label_spesifikasi"><?php echo lang('spesifikasi'); ?></label>
				<div class="col-sm-3">
					<input type="text" id="label_spesifikasi" class="form-control" name="label_spesifikasi" autocomplete="off" placeholder="<?php echo lang('label'); ?>" value="<?php echo lang('spesifikasi'); ?>" data-validation="required|max-length:100">
				</div>
				<div class="col-sm-3 col-6">
					<div class="custom-control custom-checkbox mr-sm-2">
						<input type="checkbox" class="custom-control-input chk-input" id="input_spesifikasi" name="input_spesifikasi" value="1">
						<label class="custom-control-label" for="input_spesifikasi"><?php echo lang('tampil_diinput'); ?></label>
					</div>
				</div>
				<div class="col-sm-3 col-6">
					<div class="custom-control custom-checkbox mr-sm-2">
						<input type="checkbox" class="custom-control-input chk-laporan" id="laporan_spesifikasi" name="laporan_spesifikasi" value="1">
						<label class="custom-control-label" for="laporan_spesifikasi"><?php echo lang('tampil_dilaporan'); ?></label>
					</div>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-3 required" for="label_satuan"><?php echo lang('satuan'); ?></label>
				<div class="col-sm-3">
					<input type="text" id="label_satuan" class="form-control" name="label_satuan" autocomplete="off" placeholder="<?php echo lang('label'); ?>" value="<?php echo lang('satuan'); ?>" data-validation="required|max-length:100">
				</div>
				<div class="col-sm-3 col-6">
					<div class="custom-control custom-checkbox mr-sm-2">
						<input type="checkbox" class="custom-control-input chk-input" id="input_satuan" name="input_satuan" value="1">
						<label class="custom-control-label" for="input_satuan"><?php echo lang('tampil_diinput'); ?></label>
					</div>
				</div>
				<div class="col-sm-3 col-6">
					<div class="custom-control custom-checkbox mr-sm-2">
						<input type="checkbox" class="custom-control-input chk-laporan" id="laporan_satuan" name="laporan_satuan" value="1">
						<label class="custom-control-label" for="laporan_satuan"><?php echo lang('tampil_dilaporan'); ?></label>
					</div>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-3 required" for="label_qty"><?php echo lang('jumlah'); ?></label>
				<div class="col-sm-3">
					<input type="text" id="label_qty" class="form-control" name="label_qty" autocomplete="off" placeholder="<?php echo lang('label'); ?>" value="<?php echo lang('jumlah'); ?>" data-validation="required|max-length:100">
				</div>
				<div class="col-sm-3 d-none d-sm-block">&nbsp;</div>
				<div class="col-sm-3 col-6">
					<div class="custom-control custom-checkbox mr-sm-2">
						<input type="checkbox" class="custom-control-input chk-laporan" id="laporan_qty" name="laporan_qty" value="1">
						<label class="custom-control-label" for="laporan_qty"><?php echo lang('tampil_dilaporan'); ?></label>
					</div>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-3 required" for="label_durasi"><?php echo lang('durasi'); ?></label>
				<div class="col-sm-3">
					<input type="text" id="label_durasi" class="form-control" name="label_durasi" autocomplete="off" placeholder="<?php echo lang('label'); ?>" value="<?php echo lang('durasi'); ?>" data-validation="required|max-length:100">
				</div>
				<div class="col-sm-3 col-6">
					<div class="custom-control custom-checkbox mr-sm-2">
						<input type="checkbox" class="custom-control-input chk-input" id="input_durasi" name="input_durasi" value="1">
						<label class="custom-control-label" for="input_durasi"><?php echo lang('tampil_diinput'); ?></label>
					</div>
				</div>
				<div class="col-sm-3 col-6">
					<div class="custom-control custom-checkbox mr-sm-2">
						<input type="checkbox" class="custom-control-input chk-laporan" id="laporan_durasi" name="laporan_durasi" value="1">
						<label class="custom-control-label" for="laporan_durasi"><?php echo lang('tampil_dilaporan'); ?></label>
					</div>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-3 required" for="label_harga_satuan"><?php echo lang('harga_satuan'); ?></label>
				<div class="col-sm-3">
					<input type="text" id="label_harga_satuan" class="form-control" name="label_harga_satuan" autocomplete="off" placeholder="<?php echo lang('label'); ?>" value="<?php echo lang('harga_satuan'); ?>" data-validation="required|max-length:100">
				</div>
				<div class="col-sm-3 d-none d-sm-block">&nbsp;</div>
				<div class="col-sm-3 col-6">
					<div class="custom-control custom-checkbox mr-sm-2">
						<input type="checkbox" class="custom-control-input chk-laporan" id="laporan_harga_satuan" name="laporan_harga_satuan" value="1">
						<label class="custom-control-label" for="laporan_harga_satuan"><?php echo lang('tampil_dilaporan'); ?></label>
					</div>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-3 required" for="label_fee"><?php echo lang('fee_manajemen'); ?></label>
				<div class="col-sm-3">
					<input type="text" id="label_fee" class="form-control" name="label_fee" autocomplete="off" placeholder="<?php echo lang('label'); ?>" value="<?php echo lang('fee_manajemen'); ?>" data-validation="required|max-length:100">
				</div>
				<div class="col-sm-3 col-6">
					<div class="custom-control custom-checkbox mr-sm-2">
						<input type="checkbox" class="custom-control-input chk-input" id="input_fee" name="input_fee" value="1">
						<label class="custom-control-label" for="input_fee"><?php echo lang('tampil_diinput'); ?></label>
					</div>
				</div>
				<div class="col-sm-3 col-6">
					<div class="custom-control custom-checkbox mr-sm-2">
						<input type="checkbox" class="custom-control-input chk-laporan" id="laporan_fee" name="laporan_fee" value="1">
						<label class="custom-control-label" for="laporan_fee"><?php echo lang('tampil_dilaporan'); ?></label>
					</div>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-3 required" for="label_total_harga"><?php echo lang('total_harga'); ?></label>
				<div class="col-sm-3">
					<input type="text" id="label_total_harga" class="form-control" name="label_total_harga" autocomplete="off" placeholder="<?php echo lang('label'); ?>" value="<?php echo lang('total_harga'); ?>" data-validation="required|max-length:100">
				</div>
			</div>
			<?php
			label(lang('templat_footer'));
			?>
			<div class="form-group row">
				<label class="col-form-label col-sm-3 required" for="label_total"><?php echo lang('total_harga'); ?></label>
				<div class="col-sm-3">
					<input type="text" id="label_total" class="form-control" name="label_total" autocomplete="off" placeholder="<?php echo lang('label'); ?>" value="<?php echo lang('total_harga'); ?>" data-validation="required|max-length:100">
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-3 required" for="label_fee2"><?php echo lang('fee_manajemen'); ?></label>
				<div class="col-sm-3">
					<input type="text" id="label_fee2" class="form-control" name="label_fee2" autocomplete="off" placeholder="<?php echo lang('label'); ?>" value="<?php echo lang('fee_manajemen'); ?>" data-validation="required|max-length:100">
				</div>
				<div class="col-sm-3">
					<div class="custom-control custom-checkbox mr-sm-2">
						<input type="checkbox" class="custom-control-input" id="input_fee2" name="input_fee2" value="1">
						<label class="custom-control-label" for="input_fee2"><?php echo lang('tampilkan'); ?></label>
					</div>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-3 required" for="label_jasa"><?php echo lang('jasa_pemborong'); ?></label>
				<div class="col-sm-3">
					<input type="text" id="label_jasa" class="form-control" name="label_jasa" autocomplete="off" placeholder="<?php echo lang('label'); ?>" value="<?php echo lang('jasa_pemborong'); ?>" data-validation="required|max-length:100">
				</div>
				<div class="col-sm-3">
					<div class="custom-control custom-checkbox mr-sm-2">
						<input type="checkbox" class="custom-control-input" id="input_jasa" name="input_jasa" value="1">
						<label class="custom-control-label" for="input_jasa"><?php echo lang('tampilkan'); ?></label>
					</div>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-3 required" for="label_total_sebelum_ppn"><?php echo lang('total_sebelum_ppn'); ?></label>
				<div class="col-sm-3">
					<input type="text" id="label_total_sebelum_ppn" class="form-control" name="label_total_sebelum_ppn" autocomplete="off" placeholder="<?php echo lang('label'); ?>" value="<?php echo lang('total_sebelum_ppn'); ?>" data-validation="required|max-length:100">
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-3 required" for="label_ppn"><?php echo lang('ppn'); ?></label>
				<div class="col-sm-3">
					<input type="text" id="label_ppn" class="form-control" name="label_ppn" autocomplete="off" placeholder="<?php echo lang('label'); ?>" value="<?php echo lang('ppn'); ?>">
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-3 required" for="label_total_setelah_ppn"><?php echo lang('total_setelah_ppn'); ?></label>
				<div class="col-sm-3">
					<input type="text" id="label_total_setelah_ppn" class="form-control" name="label_total_setelah_ppn" autocomplete="off" placeholder="<?php echo lang('label'); ?>" value="<?php echo lang('total_setelah_ppn'); ?>" data-validation="required|max-length:100">
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-3 required" for="label_total_pembulatan"><?php echo lang('total_pembulatan'); ?></label>
				<div class="col-sm-3">
					<input type="text" id="label_total_pembulatan" class="form-control" name="label_total_pembulatan" autocomplete="off" placeholder="<?php echo lang('label'); ?>" value="<?php echo lang('total_pembulatan'); ?>" data-validation="required|max-length:100">
				</div>
			</div>
			<?php
			form_button(lang('simpan'),lang('batal'));
		form_close();
	modal_footer();
modal_close();
?>
<script type="text/javascript">
$('.chk-input').click(function(){
	if(!$(this).is(':checked') && $(this).closest('.form-group').find('.chk-laporan').length == 1) {
		$(this).closest('.form-group').find('.chk-laporan').prop('checked',false);
	}
});
$('.chk-laporan').click(function(){
	if($(this).is(':checked') && $(this).closest('.form-group').find('.chk-input').length == 1) {
		$(this).closest('.form-group').find('.chk-input').prop('checked',true);
	}
});
</script>