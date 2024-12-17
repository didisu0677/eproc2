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
		form_open(base_url('inisiasi/pengaturan_umum/save'),'post','form','data-submit="ajax" data-callback="reload"');
			card_open(lang('pengaturan_pengadaan'),'mb-2');
				col_init(3,9);
				label(lang('minimal_rekanan_memasukan_penawaran'));
				input('text',lang('pengadaan_lelang'),'min_memasukan_lelang','required|number',setting('min_memasukan_lelang'));
				input('text',lang('pengadaan_pemilihan_langsung'),'min_memasukan_pemilihan_langsung','required|number',setting('min_memasukan_pemilihan_langsung'));
				input('text',lang('pengadaan_penunjukan_langsung'),'min_memasukan_penunjukan_langsung','required|number',setting('min_memasukan_penunjukan_langsung'));
				input('text',lang('pengadaan_jasa_langsung'),'min_memasukan_jasa_langsung','required|number',setting('min_memasukan_jasa_langsung'));
				label(lang('minimal_rekanan_lolos_penawaran'));
				input('text',lang('pengadaan_lelang'),'min_pengadaan_lelang','required|number',setting('min_pengadaan_lelang'));
				input('text',lang('pengadaan_pemilihan_langsung'),'min_pengadaan_pemilihan_langsung','required|number',setting('min_pengadaan_pemilihan_langsung'));
				input('text',lang('pengadaan_penunjukan_langsung'),'min_pengadaan_penunjukan_langsung','required|number',setting('min_pengadaan_penunjukan_langsung'));
				input('text',lang('pengadaan_jasa_langsung'),'min_pengadaan_jasa_langsung','required|number',setting('min_pengadaan_jasa_langsung'));
			card_close();
			card_open(lang('tanda_tangan'));
				foreach($tanda_tangan as $ttd) {
					$nama 	= $ttd['nama'] ? $ttd['nama'].' | '.$ttd['jabatan'] : '';
					?>
					<div class="form-group row">
						<label class="col-form-label col-sm-3 required" for="nama_pengguna_<?php echo $ttd['_key']; ?>"><?php echo $ttd['_label']; ?></label>
						<div class="col-sm-9">
							<input type="hidden" name="id_user[<?php echo $ttd['_key']; ?>]" id="id_user_<?php echo $ttd['_key']; ?>" value="<?php echo $ttd['id_user']; ?>">
							<input type="text" name="nama_pengguna[<?php echo $ttd['_key']; ?>]" autocomplete="off" class="form-control autocomplete" data-validation="required" value="<?php echo $nama; ?>" data-source="<?php echo base_url('inisiasi/pengaturan_umum/get_user'); ?>" data-target="id_user_<?php echo $ttd['_key']; ?>">
						</div>
					</div>
					<?php
				}
				form_button(lang('simpan'),false);
			card_close();
		form_close();
		?>
	</div>
</div>
