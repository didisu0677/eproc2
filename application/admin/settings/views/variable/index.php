<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<div class="float-right">
			<?php echo access_button('delete,active,inactive,export,import'); ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body">
	<?php
	table_open('',true,base_url('settings/variable/data'),'tbl_m_variable');
		thead();
			tr();
				th('checkbox','text-center','width="30" data-content="id"');
				th(lang('panitia'),'','data-content="nama_panitia"');
				th(lang('lokasi_pengadaan'),'','data-content="lokasi_pengadaan"');
				th(lang('nama_pemberi_tugas'),'','data-content="pemberi_tugas"');
				th(lang('peserta_kurang_dari'),'','data-content="peserta_kurang_dari"');
				th(lang('peserta_sah_kurang_dari'),'','data-content="peserta_sah_kurang_dari"');
				th(lang('bayar_lewat'),'','data-content="bayar_lewat"');
				th(lang('bayar_di'),'','data-content="bayar_di"');
				th(lang('lokasi'),'','data-content="lokasi"');
				th(lang('ttd'),'','data-content="ttd"');
				th(lang('jabatan'),'','data-content="jabatan"');
				th(lang('aktif').'?','text-center','data-content="is_active" data-type="boolean"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<?php
modal_open('modal-form');
	modal_body();
		form_open(base_url('settings/variable/save'),'post','form');
			col_init(3,9);
			input('hidden','id','id');
			?>
			<div class="form-group row">
				<label class="col-form-label col-sm-3 required" for="nomor_pengadaan">Mata Anggaran</label>
				<div class="col-sm-9">
					<select name="panitia_id" id="panitia_id" class="form-control select2" data-validation="required">
						<option value=""></option>
						<?php foreach ($panitia as $ma){ ?>
							<option value="<?php echo $ma['id'] ?>"><?php echo $ma['nama']; ?></option>
						<?php } ?>

					</select>
				</div>
			</div>
			<?php
			input('text',lang('lokasi_pengadaan'),'lokasi_pengadaan');
			input('text',lang('nama_pemberi_tugas'),'pemberi_tugas');
			input('text',lang('peserta_kurang_dari'),'peserta_kurang_dari');
			input('text',lang('peserta_sah_kurang_dari'),'peserta_sah_kurang_dari');
			input('text',lang('bayar_lewat'),'bayar_lewat');
			input('text',lang('bayar_di'),'bayar_di');
			input('text',lang('lokasi'),'lokasi');
			input('text',lang('ttd'),'ttd');
			input('text',lang('jabatan'),'jabatan');
			toggle(lang('aktif').'?','is_active');
			form_button(lang('simpan'),lang('batal'));
		form_close();
	modal_footer();
modal_close();
modal_open('modal-import',lang('impor'));
	modal_body();
		form_open(base_url('settings/variable/import'),'post','form-import');
			col_init(3,9);
			fileupload('File Excel','fileimport','required','data-accept="xls|xlsx"');
			form_button(lang('impor'),lang('batal'));
		form_close();
modal_close();
?>
