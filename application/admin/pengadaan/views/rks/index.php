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
	table_open('',true,base_url('pengadaan/rks/data'),'tbl_rks');
		thead();
			tr();
				th(lang('no'),'text-center','width="30" data-content="id"');
				th(lang('nomor_rks'),'','data-content="nomor_rks"');
				th(lang('tanggal_rks'),'','data-content="tanggal_rks" data-type="daterange"');
				th(lang('nama_divisi'),'','data-content="nama_divisi"');
				th(lang('nama_pengadaan'),'','data-content="nama_pengadaan"');
				th(lang('nama_pemberi_tugas'),'','data-content="pemberi_tugas"');
				th(lang('mata_anggaran'),'','data-content="mata_anggaran"');
				th(lang('hps_panitia'),'','data-content="hps_panitia" data-type="currency"');
				th(lang('status'),'','width="150" data-content="status" data-replace="0:'.lang('draf').'|1:'.lang('diproses').'|2:'.lang('disetujui').'|8:'.lang('dikembalikan').'|9:'.lang('ditolak').'"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<?php
modal_open('modal-form','','modal-xl','data-openCallback="formOpen"');
	modal_body('wizard');
		form_open(base_url('pengadaan/rks/save'),'post','form'); ?>
		<ul class="nav nav-tabs" id="tab-wizard" role="tablist">
			<li class="nav-item">
				<a class="nav-link active" id="step1-tab" data-toggle="tab" href="#step1" role="tab" aria-controls="step1" aria-selected="true"><?php echo lang('informasi_rks'); ?></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="step2-tab" data-toggle="tab" href="#step2" role="tab" aria-controls="step2" aria-selected="off"><?php echo lang('syarat_ketentuan'); ?></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="step3-tab" data-toggle="tab" href="#step3" role="tab" aria-controls="step3" aria-selected="off"><?php echo lang('tor'); ?></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="step4-tab" data-toggle="tab" href="#step4" role="tab" aria-controls="step4" aria-selected="off"><?php echo lang('jadwal_pengadaan'); ?></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" id="step5-tab" data-toggle="tab" href="#step5" role="tab" aria-controls="step5" aria-selected="off"><?php echo lang('dokumen_pendukung'); ?></a>
			</li>
		</ul>
		<div class="tab-content" id="tab-wizardContent">
			<div class="tab-pane show active" id="step1" role="tabpanel" aria-labelledby="step1-tab">
				<?php
				col_init(3,9);
				input('hidden','id','id');
				select2(lang('nomor_pengajuan'),'nomor_pengajuan','required');
				input('text',lang('nama_pemberi_tugas'),'pemberi_tugas','','','data-readonly readonly');
				input('text',lang('nama_pengadaan'),'nama_pengadaan','','','data-readonly readonly');
				input('text',lang('mata_anggaran'),'mata_anggaran','','','data-readonly readonly');
				input('money',lang('usulan_hps'),'usulan_hps','','','data-readonlye readonly');
				input('money',lang('hps_panitia'),'hps_panitia','','','data-readonlye readonly');
				input('text',lang('nama_divisi'),'nama_divisi','','','data-readonly readonly');
				input('text',lang('metode_pengadaan'),'metode_pengadaan','','','data-readonly readonly');
				input('text',lang('jenis_pengadaan'),'jenis_pengadaan','','','data-readonly readonly');
				input('text',lang('nomor_rks'),'nomor_rks','required|unique');
				input('date',lang('tanggal_rks'),'tanggal_rks','required');
				?>
				<div class="form-group row">
					<label class="col-form-label col-sm-3 required"><?php echo lang('batas_hps'); ?></label>
					<div class="col-md-3 col-6">
						<input type="text" id="batas_hps_bawah" name="batas_hps_bawah" autocomplete="off" class="form-control percent" placeholder="<?php echo lang('batas_minimal'); ?>" data-validation="required" data-append="%">
					</div>
					<div class="col-md-3 col-6">
						<input type="text" id="batas_hps_atas" name="batas_hps_atas" autocomplete="off" class="form-control percent" placeholder="<?php echo lang('batas_maksimal'); ?>" data-validation="required" data-append="%">
					</div>
				</div>
				<?php
				toggle(lang('pasal_jaminan_penawaran'),'jaminan_penawaran');
				toggle(lang('pasal_jaminan_pelaksanaan'),'jaminan_pelaksanaan');
				toggle(lang('pasal_jaminan_pemeliharaan'),'jaminan_pemeliharaan');
				label(lang('tanda_tangan_oleh'));
				input('text',lang('nama'),'nama_tanda_tangan','required|max-length:100');
				input('text',lang('jabatan'),'jabatan_tanda_tangan','required|max-length:100');
				?>
				<div class="form-group row">
					<div class="col-sm-9 offset-sm-3">
						<button type="reset" class="btn btn-secondary"><?php echo lang('batal'); ?></button>
						<button type="button" class="btn btn-success btn-next" data-target="step2" data-trigger="checkBatasHps"><?php echo lang('selanjutnya'); ?></button>
					</div>
				</div>
			</div>
			<div class="tab-pane" id="step2" role="tabpanel" aria-labelledby="step2-tab">
				<?php
				col_init(3,9);
				textarea(lang('syarat_umum'),'syarat_umum','required','','data-editor="inline"');
				textarea(lang('syarat_khusus'),'syarat_khusus','required','','data-editor="inline"');
				textarea(lang('syarat_teknis'),'syarat_teknis','required','','data-editor="inline"');
				textarea(lang('pola_pembayaran'),'pola_pembayaran','required','','data-editor="inline"');
				toggle(lang('sanggahan_peserta'),'sanggahan_peserta');
				?>
				<div class="form-group row">
					<div class="col-sm-9 offset-sm-3">
						<button type="button" class="btn btn-danger btn-prev" data-target="step1"><?php echo lang('sebelumnya'); ?></button>
						<button type="button" class="btn btn-success btn-next" data-target="step3"><?php echo lang('selanjutnya'); ?></button>
					</div>
				</div>
			</div>
			<div class="tab-pane" id="step3" role="tabpanel" aria-labelledby="step3-tab">
				<?php
				textarea(lang('latar_belakang'),'latar_belakang','required','','data-editor="inline"');
				textarea(lang('spesifikasi'),'spesifikasi','required','','data-editor="inline"');
				textarea(lang('jumlah_kebutuhan'),'jumlah_kebutuhan','required','','data-editor="inline"');
				textarea(lang('distribusi_kebutuhan'),'distribusi_kebutuhan','required','','data-editor="inline"');
				textarea(lang('jangka_waktu'),'jangka_waktu','required','','data-editor="inline"');
				textarea(lang('ruang_lingkup'),'ruang_lingkup','required','','data-editor="inline"');
				textarea(lang('lain_lain'),'lain_lain','required','','data-editor="inline"');
				?>
				<div class="form-group row">
					<div class="col-sm-9 offset-sm-3">
						<button type="button" class="btn btn-danger btn-prev" data-target="step2"><?php echo lang('sebelumnya'); ?></button>
						<button type="button" class="btn btn-success btn-next" data-target="step4"><?php echo lang('selanjutnya'); ?></button>
					</div>
				</div>
			</div>
			<div class="tab-pane" id="step4" role="tabpanel" aria-labelledby="step4-tab">
				<?php foreach($jadwal as $j) { ?>
				<div class="form-group row">
					<label class="col-form-label col-sm-3<?php if(in_array($j['kata_kunci'],$mandatori)) echo ' required'; ?>"><?php echo $j['jadwal']; ?></label>
					<div class="col-md-3 mb-1 mb-md-0">
						<input type="hidden" name="jadwal[<?php echo $j['id']; ?>]" value="<?php echo $j['id']; ?>" data-value="<?php echo $j['id']; ?>" class="jadwal">
						<textarea name="lokasi[<?php echo $j['id']; ?>]" class="form-control lokasi" placeholder="<?php echo lang('lokasi'); ?>"<?php if(in_array($j['kata_kunci'],$mandatori)) echo ' data-validation="required"'; ?>></textarea>
					</div>
					<div class="col-md-2 col-6 mb-1 mb-md-0">
						<input type="text" name="tanggal_awal[<?php echo $j['id']; ?>]" autocomplete="off" class="form-control dtp tanggal_awal" placeholder="<?php echo lang('tanggal_mulai'); ?>" aria-label="<?php echo lang('tanggal_mulai'); ?>"<?php if(in_array($j['kata_kunci'],$mandatori)) echo ' data-validation="required"'; ?>>
					</div>
					<div class="col-md-2 col-6 mb-1 mb-md-0">
						<input type="text" name="tanggal_akhir[<?php echo $j['id']; ?>]" autocomplete="off" class="form-control dtp tanggal_akhir" placeholder="<?php echo lang('tanggal_selesai'); ?>" aria-label="<?php echo lang('tanggal_selesai'); ?>"<?php if(in_array($j['kata_kunci'],$mandatori)) echo ' data-validation="required"'; else echo ' data-validation=""' ?>>
					</div>
					<div class="col-md-2">
						<select name="zona_waktu[<?php echo $j['id']; ?>]" autocomplete="off" class="form-control select2 infinity zona">
							<option value="WIB">WIB</option>
							<option value="WITA">WITA</option>
							<option value="WIT">WIT</option>
						</select>
					</div>
				</div>
				<?php } ?>
				<div class="form-group row">
					<div class="col-sm-9 offset-sm-3">
						<button type="button" class="btn btn-danger btn-prev" data-target="step3"><?php echo lang('sebelumnya'); ?></button>
						<button type="button" class="btn btn-success btn-next" data-trigger="checkTanggal" data-target="step5"><?php echo lang('selanjutnya'); ?></button>
					</div>
				</div>
			</div>
			<div class="tab-pane" id="step5" role="tabpanel" aria-labelledby="step5-tab">
				<div class="form-group row">
					<label class="col-form-label col-sm-3"><?php echo lang('dokumen_pendukung') ?><small><?php echo lang('maksimal'); ?> 5MB</small></label>
					<div class="col-sm-9">
						<button type="button" class="btn btn-info" id="add-file" title="<?php echo lang('tambah_dokumen'); ?>"><?php echo lang('tambah_dokumen'); ?></button>
					</div>
				</div>
				<div id="additional-file" class="mb-2"></div>
				<div class="form-group row">
					<div class="col-sm-9 offset-sm-3">
						<button type="button" class="btn btn-danger btn-prev" data-target="step4"><?php echo lang('sebelumnya'); ?></button>
						<button type="submit" class="btn btn-success"><?php echo lang('simpan'); ?></button>
					</div>
				</div>
			</div>
		</div>
		<?php
		form_close();
	modal_footer();
modal_close(); ?>
<form action="<?php echo base_url('upload/file/datetime'); ?>" class="hidden">
	<input type="hidden" name="name" value="field_document">
	<input type="hidden" name="token" value="<?php echo encode_id([user('id'),(time() + 900)]); ?>">
	<input type="file" name="document" id="upl-file">
</form>
<script type="text/javascript" src="<?php echo base_url('assets/plugins/ckeditor/ckeditor.js') ?>"></script>
<script>
var pengajuan = {};
var jadwal = {};
function formOpen() {
	var response = response_edit;
	$('#additional-file').html('');
	$('.jadwal').each(function(){
		$(this).val($(this).attr('data-value'));
	});
	$('.zona').val('WIB').trigger('change');
	if(typeof response.id != 'undefined') {
		$('#nomor_pengajuan').html('<option value="'+response.nomor_pengajuan+'">'+response.nomor_pengajuan+' | '+response.nama_pengadaan+'</option>').trigger('change');
		$.each(response.detail,function(n,z){
			$('[name="lokasi['+z.id_m_penjadwalan+']"]').val(z.lokasi);
			$('[name="tanggal_awal['+z.id_m_penjadwalan+']"]').val(cDate(z.tanggal_awal,true));
			$('[name="tanggal_akhir['+z.id_m_penjadwalan+']"]').val(cDate(z.tanggal_akhir,true));
			$('[name="zona_waktu['+z.id_m_penjadwalan+']"]').val(z.zona_waktu).trigger('change');
		});
		$.each(response.file,function(n,z){
			var konten = '<div class="form-group row">'
				+ '<div class="col-sm-3 col-4 offset-sm-3">'
				+ '<input type="text" class="form-control" autocomplete="off" value="'+n+'" name="keterangan_file[]" placeholder="'+lang.keterangan+'" data-validation="required" aria-label="'+lang.keterangan+'">'
				+ '</div>'
				+ '<div class="col-sm-4 col-5">'
				+ '<input type="hidden" class="form-control" name="file[]" autocomplete="off" value="exist:'+z+'">'
				+ '<div class="input-group">'
				+ '<input type="text" class="form-control" autocomplete="off" disabled value="'+z+'">'
				+ '<div class="input-group-append">'
				+ '<a href="'+base_url+'assets/uploads/rks/'+z+'" target="_blank" class="btn btn-info btn-icon-only"><i class="fa-download"></i></a>'
				+ '</div>'
				+ '</div>'
				+ '</div>'
				+ '<div class="col-sm-2 col-3">'
				+ '<button type="button" class="btn btn-danger btn-remove btn-block btn-icon-only"><i class="fa-times"></i></button>'
				+ '</div>'
				+ '</div>';
			$('#additional-file').append(konten);
		});
	} else {
		view_combo();
	}
}
$(document).on('click','.btn-remove',function(){
	$(this).closest('.form-group').remove();
});
function view_combo() {
	$.ajax({
		url : base_url + 'pengadaan/rks/get_combo',
		dataType : 'json',
		success : function(response){
			pengajuan 	= response.pengajuan;
			jadwal 		= response.jadwal;
			var konten 	= '<option value=""></option>';
			$.each(pengajuan,function(k,v){
				konten += '<option value="'+v.nomor_pengajuan+'">'+v.nomor_pengajuan+' | '+v.nama_pengadaan+'</option>';
			});
			$('#nomor_pengajuan').html(konten).trigger('change');
		}
	});
}
$('#nomor_pengajuan').change(function(){
	if(typeof pengajuan[$(this).val()] !== 'undefined') {
		var p = pengajuan[$(this).val()];
		$('#nama_pengadaan').val(p.nama_pengadaan);
		$('#pemberi_tugas').val(p.pemberi_tugas);
		$('#mata_anggaran').val(p.mata_anggaran);
		$('#usulan_hps').val(customFormat(p.usulan_hps));
		$('#hps_panitia').val(customFormat(p.hps_panitia));
		$('#nama_divisi').val(p.nama_divisi);
		$('#metode_pengadaan').val(p.metode_pengadaan);
		$('#jenis_pengadaan').val(p.jenis_pengadaan);

		var tor = ['latar_belakang','spesifikasi','jumlah_kebutuhan','distribusi_kebutuhan','ruang_lingkup','jangka_waktu','lain_lain'];
		$.each(tor,function(k,v){
			$('#'+v).val(p[v]);
			CKEDITOR.instances[v].setData(decodeEntities(p[v]));
		});
	}
	$('.lokasi, .tanggal_awal, .tanggal_akhir').val('');
	if(typeof jadwal[$(this).val()] !== 'undefined') {
		$.each(jadwal[$(this).val()],function(n,z){
			$('[name="lokasi['+z.id_m_penjadwalan+']"]').val(z.lokasi);
			$('[name="tanggal_awal['+z.id_m_penjadwalan+']"]').val(cDate(z.tanggal_awal,true));
			$('[name="tanggal_akhir['+z.id_m_penjadwalan+']"]').val(cDate(z.tanggal_akhir,true));
			$('[name="zona_waktu['+z.id_m_penjadwalan+']"]').val(z.zona_waktu).trigger('change');
		});
	}
});
$('#add-file').click(function(){
	$('#upl-file').click();
});
var accept 	= Base64.decode(upl_alw);
var regex 	= "(\.|\/)("+accept+")$";
var re 		= accept == '*' ? '*' : new RegExp(regex,"i");
$('#upl-file').fileupload({
	maxFileSize: upl_flsz,
	autoUpload: false,
	dataType: 'text',
	acceptFileTypes: re
}).on('fileuploadadd', function(e, data) {
	$('#add-file').attr('disabled',true);
	data.process();
	is_autocomplete = true;
}).on('fileuploadprocessalways', function (e, data) {
	if (data.files.error) {
		var explode = accept.split('|');
		var acc 	= '';
		$.each(explode,function(i){
			if(i == 0) {
				acc += '*.' + explode[i];
			} else if (i == explode.length - 1) {
				acc += ', ' + lang.atau + ' *.' + explode[i];
			} else {
				acc += ', *.' + explode[i];
			}
		});
		cAlert.open(lang.file_yang_diizinkan + ' ' + acc + '. ' + lang.ukuran_file_maks + ' : ' + (upl_flsz / 1024 / 1024) + 'MB');
		$('#add-file').text($('#add-file').attr('title')).removeAttr('disabled');
	} else {
		data.submit();
	}
	is_autocomplete = false;
}).on('fileuploadprogressall', function (e, data) {
	var progress = parseInt(data.loaded / data.total * 100, 10);
	$('#add-file').text(progress + '%');
}).on('fileuploaddone', function (e, data) {
	if(data.result == 'invalid' || data.result == '') {
		cAlert.open(lang.gagal_menunggah_file,'error');
	} else {
		var spl_result = data.result.split('/');
		if(spl_result.length == 1) spl_result = data.result.split('\\');
		if(spl_result.length > 1) {
			var spl_last_str = spl_result[spl_result.length - 1].split('.');
			if(spl_last_str.length == 2) {
				var filename = data.result;
				var f = filename.split('/');
				var fl = filename.split('temp');
				var fl_link = base_url + 'assets/uploads/temp' + fl[1];
				var konten = '<div class="form-group row">'
							+ '<div class="col-sm-3 col-4 offset-sm-3">'
							+ '<input type="text" class="form-control" autocomplete="off" value="" name="keterangan_file[]" placeholder="'+lang.keterangan+'" data-validation="required" aria-label="'+lang.keterangan+'">'
							+ '</div>'
							+ '<div class="col-sm-4 col-5">'
							+ '<input type="hidden" class="form-control" name="file[]" autocomplete="off" value="'+data.result+'">'
							+ '<div class="input-group">'
							+ '<input type="text" class="form-control" autocomplete="off" disabled value="'+f[f.length - 1]+'">'
							+ '<div class="input-group-append">'
							+ '<a href="'+fl_link+'" target="_blank" class="btn btn-info btn-icon-only"><i class="fa-download"></i></a>'
							+ '</div>'
							+ '</div>'
							+ '</div>'
							+ '<div class="col-sm-2 col-3">'
							+ '<button type="button" class="btn btn-danger btn-remove btn-block btn-icon-only"><i class="fa-times"></i></button>'
							+ '</div>'
							+ '</div>';
				$('#additional-file').append(konten);
			} else {
				cAlert.open(lang.file_gagal_diunggah,'error');
			}
		} else {
			cAlert.open(lang.file_gagal_diunggah,'error');						
		}
	}
	$('#add-file').text($('#add-file').attr('title')).removeAttr('disabled');
	is_autocomplete = false;
}).on('fileuploadfail', function (e, data) {
	cAlert.open(lang.gagal_menunggah_file,'error');
	$('#add-file').text($('#add-file').attr('title')).removeAttr('disabled');
	is_autocomplete = false;
}).on('fileuploadalways', function() {
});
$(document).on('click','.detail-pengajuan',function(){
	$.get(base_url+'pengadaan/pengajuan/detail?no_pengajuan='+$(this).attr('data-value'),function(result){
		cInfo.open(lang.detil,result);
	});
});
$(document).on('click','.btn-print',function(){
	var id = encodeId($(this).attr('data-id'));
	$.redirect(base_url + 'pengadaan/rks/cetak/' + id, {} , 'get', '_blank');
});
function checkTanggal() {
	var res = true;
	$('.tanggal_akhir').each(function(){
		var tanggal_akhir 	= $(this).val();
		var tanggal_awal	= $(this).closest('.row').find('.tanggal_awal').val();
		if(tanggal_akhir) {
			var x_akhir = tanggal_akhir.split(' ');
			var x_awal 	= tanggal_awal.split(' ');
			if(x_akhir.length == 2 && x_awal.length == 2) {
				var d_akhir = x_akhir[0].split('/');
				var t_akhir = x_akhir[1].split(':');
				var akhir 	= d_akhir[2] + '-' + d_akhir[1] + '-' + d_akhir[0] + ' ' + t_akhir[0] + ':';
				akhir 		+= typeof t_akhir[1] !== 'undefined' && t_akhir[1] ? t_akhir[1]+':00' : '00:00';
				var time_akhir = new Date(akhir).getTime();

				var d_awal 	= x_awal[0].split('/');
				var t_awal 	= x_awal[1].split(':');
				var awal 	= d_awal[2] + '-' + d_awal[1] + '-' + d_awal[0] + ' ' + t_awal[0] + ':';
				awal 		+= typeof t_awal[1] !== 'undefined' && t_awal[1] ? t_awal[1]+':00' : '00:00';
				var time_awal = new Date(awal).getTime();

				if(parseInt(time_awal) >= parseInt(time_akhir)) {
					res = false;
					if($(this).parent().find('span.error').length == 0) {
						$(this).addClass('is-invalid');
						$(this).parent().append('<span class="error">' + lang.tidak_boleh_lebih_awal_dari_tanggal_mulai + '</span>');
					}
				}
			} else {
				$(this).val('');
				$(this).closest('.row').find('.tanggal_awal').val('');
				$(this).closest('.row').find('.lokasi').val('');
			}
		}
	});
	return res;
}
$('.tanggal_awal').on('apply.daterangepicker', function(ev, picker) {
	var tgl = $(this).closest('.row').find('.tanggal_akhir');
	tgl.removeClass('is-invalid');
	tgl.parent().find('span.error').remove();
});
function checkBatasHps() {
	var res 	= true;
	if( toNumber($('#batas_hps_atas').val()) < toNumber($('#batas_hps_bawah').val())) {
		if($('#batas_hps_atas').parent().find('span.error').length == 0) {
			$('#batas_hps_atas').addClass('is-invalid');
			$('#batas_hps_atas').parent().append('<span class="error">' + lang.tidak_boleh_lebih_kecil_dari_batas_hps_minimal + '</span>');
		}
		res = false;
	}
	return res;
}
</script>
