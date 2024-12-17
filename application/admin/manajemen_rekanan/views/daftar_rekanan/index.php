<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<div class="float-right">
			<select class="select2 infinity custom-select" id="filter">
				<option value="rekanan"><?php echo lang('daftar_rekanan'); ?></option>
				<option value="blacklist"><?php echo lang('daftar_blacklist'); ?></option>
				<option value="spk"><?php echo lang('pemegang_spk'); ?></option>
			</select>
			<?php echo access_button(); ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body">
	<?php
	table_open('',true,base_url('manajemen_rekanan/daftar_rekanan/data'),'tbl_vendor');
		thead();
			tr();
				th(lang('no'),'text-center','width="30" data-content="id"');
				th(lang('kode_rekanan'),'','data-content="kode_rekanan"');
				th(lang('nama_rekanan'),'','data-content="nama"');
				th(lang('jenis_rekanan'),'','data-content="jenis_rekanan" data-replace="1:'.lang('badan_usaha').'|2:'.lang('perorangan').'"');
				th(lang('kategori_rekanan'),'','data-content="kategori_rekanan"');
				th(lang('kualifikasi'),'','data-content="kualifikasi"');
				th(lang('asosiasi'),'','data-content="asosiasi"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<?php 
modal_open('modal-form','','modal-lg','data-manual="true"');
	modal_body();
		form_open(base_url('manajemen_rekanan/daftar_rekanan/save'),'post','form');
			col_init(3,9);
			input('hidden','id','id');
			?>
			<div class="form-group row">
				<label class="col-form-label col-sm-3" for="jenis_rekanan"><?php echo lang('jenis_rekanan'); ?></label>
				<div class="col-sm-9">
					<select id="jenis_rekanan" name="jenis_rekanan" data-validation="required" class="form-control select2 infinity">
						<option value="1"><?php echo lang('badan_usaha'); ?></option>
						<option value="2"><?php echo lang('perorangan'); ?></option>
					</select>
				</div>
			</div>

			<?php
			label(strtoupper(lang('informasi_umum')),'mb-2 mt-2');
			?>
			<div class="form-group row">
				<label class="col-form-label col-sm-3 required" for="kode_rekanan"><?php echo lang('kode_rekanan'); ?></label>
				<div class="col-sm-9">
					<div class="input-group">
						<input type="text" name="kode_rekanan" id="kode_rekanan" class="form-control" autocomplete="off" data-validation="required|unique|max-length:30" />
						<div class="input-group-append">
							<span class="input-group-text" data-toggle="tooltip" title="<?php echo lang('kode_rekanan_as_username'); ?>"><i class="fa-question-circle"></i></span>
						</div>
					</div>
				</div>
			</div>
			<?php
			input('text',lang('kode_sap'),'kode_sap','required|max-length:30','','data-readonlye="true" readonly');
			input('text',lang('nama_rekanan'),'nama','required|max-length:100');
			input('text',lang('npwp_rekanan'),'npwp','required|max-length:30');
			input('text',lang('nama_pimpinan'),'nama_pimpinan','required|nama_pimpinan|max-length:150');
			input('text',lang('jabatan_pimpinan'),'jabatan_pimpinan','required|jabatan_pimpinan|max-length:150');
			select2(lang('kategori_rekanan'),'id_kategori_rekanan[]','required',$kategori_rekanan,'id','kategori','','multiple');
			?>
			<div class="form-group row">
				<label class="col-form-label col-sm-3 required" for="no_identitas"><?php echo lang('nomor_identitas'); ?></label>
				<div class="col-sm-5">
					<input type="text" name="no_identitas" id="no_identitas" class="form-control" autocomplete="off" data-validation="required|max-length:30" />
				</div>
				<div class="col-sm-4 mt-2 mt-sm-0">
					<input type="text" name="tanggal_berakhir_identitas" id="tanggal_berakhir_identitas" autocomplete="off" class="form-control dp" data-validation="required" placeholder="<?php echo lang('berlaku_sampai'); ?>">
				</div>
			</div>
			<?php
			select2(lang('kelompok_rekanan'),'kode_kelompok_rekanan','required',$kelompok_vendor,'kode','kode : kelompok');
			select2(lang('bentuk_badan_usaha'),'id_bentuk_badan_usaha','required',$bentuk_badan_usaha,'id','bentuk_badan_usaha');
			select2(lang('status_perusahaan'),'id_status_perusahaan','required',$status_perusahaan,'id','status_perusahaan');
			select2(lang('kualifikasi'),'id_kualifikasi','required',$kualifikasi,'id','kualifikasi');
			select2(lang('asosiasi'),'id_asosiasi','required',$asosiasi,'id','asosiasi');
			select2(lang('mendaftar_di_unit'),'id_unit_daftar','required',$unit,'id','unit');
			label(strtoupper(lang('alamat_lengkap')),'mb-2 mt-2');
			textarea(lang('alamat'),'alamat','required');
			col_init(3,5);
			select2(lang('negara'),'id_negara','required',$negara,'id','nama','101');
			?>
			<div class="form-group row">
				<label class="col-form-label col-sm-3 required" for="id_provinsi"><?php echo lang('provinsi'); ?></label>
				<div class="col-sm-5">
					<select name="id_provinsi" id="id_provinsi" class="form-control select2" data-validation="required">
						<option value=""></option>
						<?php foreach($provinsi as $p) { ?>
						<option value="<?php echo $p['id']; ?>"><?php echo $p['nama']; ?></option>
						<?php } ?>
						<option value="999"><?php echo lang('lainnya'); ?></option>
					</select>
				</div>
				<div class="col-sm-4 mt-2 mt-sm-0 hidden">
					<input type="text" name="nama_provinsi" id="nama_provinsi" autocomplete="off" class="form-control" data-validation="required|max-length:50" value="" placeholder="<?php echo lang('nama_provinsi'); ?>">
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-3 required" for="id_kota"><?php echo lang('kota'); ?></label>
				<div class="col-sm-5">
					<select name="id_kota" id="id_kota" class="form-control select2" data-validation="required">
						<option value=""></option>
					</select>
				</div>
				<div class="col-sm-4 mt-2 mt-sm-0 hidden">
					<input type="text" name="nama_kota" id="nama_kota" autocomplete="off" class="form-control" data-validation="required|max-length:50" value="" placeholder="<?php echo lang('nama_kota'); ?>">
				</div>
			</div>
			<div class="form-group row">
				<label class="col-form-label col-sm-3 required" for="id_kecamatan"><?php echo lang('kecamatan'); ?></label>
				<div class="col-sm-5">
					<select name="id_kecamatan" id="id_kecamatan" class="form-control select2" data-validation="required">
						<option value=""></option>
					</select>
				</div>
				<div class="col-sm-4 mt-2 mt-sm-0 hidden">
					<input type="text" name="nama_kecamatan" id="nama_kecamatan" autocomplete="off" class="form-control" data-validation="required|max-length:50" value="" placeholder="<?php echo lang('nama_kecamatan'); ?>">
				</div>
			</div>

			<div class="form-group row">
				<label class="col-form-label col-sm-3 required" for="id_kelurahan"><?php echo lang('kelurahan'); ?></label>
				<div class="col-sm-5">
					<select name="id_kelurahan" id="id_kelurahan" class="form-control select2" data-validation="required">
						<option value=""></option>
					</select>
				</div>

				<div class="col-sm-4 mt-2 mt-sm-0 hidden">
					<input type="text" name="nama_kelurahan" id="nama_kelurahan" autocomplete="off" class="form-control" data-validation="required|max-length:50" value="" placeholder="<?php echo lang('nama_kelurahan'); ?>">
				</div>
			</div>
			<?php
			input('text',lang('kode_pos'),'kode_pos','required|length:5|number');
			col_init(3,9);
			input('text',lang('no_telepon'),'no_telepon','required|phone|max-length:30');
			input('text',lang('no_fax'),'no_fax','required|phone|max-length:30');
			input('text',lang('email'),'email','required|email|max-length:50');
			label(strtoupper(lang('kontak_person')),'mb-2 mt-2');
			input('text',lang('nama'),'nama_cp','required|max-length:100');
			input('text',lang('hp'),'hp_cp','required|phone|max-length:30');
			input('text',lang('email') . ' (seluruh notifikasi ke vendor menggunakan email ini)','email_cp','required|email|unique|max-length:100');
			label(strtoupper(lang('data_bank')),'mb-2 mt-2');
			select2(lang('kode_bank'),'kode_bank','',$kode_bank,'kode','kode : deskripsi');
			input('text',lang('nomor_rekening'),'nomor_rekening','max-length:50');
			input('text',lang('pemilik_rekening'),'pemilik_rekening','max-length:100');
			select2(lang('recont_account'),'kode_recont','',$recont,'kode','kode : deskripsi');
			form_button(lang('simpan'),lang('batal'));
		form_close();
	modal_footer();

modal_close();
modal_open('modal-change-password',lang('ubah_kata_sandi'));
	modal_body();
		form_open(base_url('manajemen_rekanan/daftar_rekanan/change_password'),'post','form-change-password');
			col_init(4,8);
			input('hidden','id','id_vendor');
			input('password',lang('kata_sandi'),'password','required|min-length:8|strong_password');
			input('password',lang('konfirmasi_kata_sandi'),'konfirmasi','required|equal:password');
			form_button(lang('ubah_kata_sandi'),lang('batal'));
		form_close();
modal_close();
?>

<script type="text/javascript">
var is_edit = false;
var __id = 0;
var id_unlock = 0;
$(document).on('click','.btn-unlock',function(e){
	e.preventDefault();
	id_unlock = $(this).attr('data-id');
	cConfirm.open(lang.apakah_anda_yakin + '?','lanjut');
});
function lanjut() {
	$.ajax({
		url : base_url + 'manajemen_rekanan/daftar_rekanan/unlock',
		data : {id:id_unlock},
		type : 'post',
		dataType : 'json',
		success : function(res) {
			cAlert.open(res.message,res.status,'refreshData');
		}
	});
}
$('#filter').change(function(){
	var url = base_url + 'manajemen_rekanan/daftar_rekanan/data/' + $(this).val();
	$('[data-serverside]').attr('data-serverside',url);
	refreshData();
});

function detail_callback(e) {
	$.get(base_url + 'manajemen_rekanan/daftar_rekanan/detail/' + e, function(response){
		cInfo.open(lang.detil,response);
	});
}

function badan_usaha() {
	$('#no_identitas').closest('.form-group').addClass('hidden');
	$('#no_identitas,#tanggal_berakhir_identitas').val('');
	$('input[name="validation_no_identitas"],input[name="validation_tanggal_berakhir_identitas"]').val('');
	$('input[name="validation_id_bentuk_badan_usaha"],input[name="validation_id_status_perusahaan"]').val('required');
	$('#id_bentuk_badan_usaha,#id_status_perusahaan').closest('.form-group').removeClass('hidden');
}

function perorangan() {
	$('#no_identitas').closest('.form-group').removeClass('hidden');
	$('#id_bentuk_badan_usaha,#id_status_perusahaan').closest('.form-group').addClass('hidden');
	$('#id_bentuk_badan_usaha,#id_status_perusahaan').val('').trigger('change');
	$('input[name="validation_id_bentuk_badan_usaha"],input[name="validation_id_status_perusahaan"]').val('');
	$('input[name="validation_no_identitas"],input[name="validation_tanggal_berakhir_identitas"]').val('required');
}

$(document).on('click','.btn-input',function(){
	var id = $(this).attr('data-id');
	if(id == '0') {
		is_edit = false;
		$('#modal-form .modal-title').text(lang.tambah);
		$('#modal-form button[type="submit"]').text(lang.simpan);
		$('#modal-form .modal-footer').hide();
		$('#modal-form form')[0].reset();
		$('select').trigger('change');
		$('#modal-form').modal();
	} else {
		$('#modal-form .modal-title').text(lang.ubah);
		$('#modal-form button[type="submit"]').text(lang.perbaharui);
		$('#modal-form form')[0].reset();
		$('select').trigger('change');
		is_edit = true;
		$.ajax({
			url : base_url + 'manajemen_rekanan/daftar_rekanan/get_data',
			data : {id:id},
			dataType : 'json',
			type : 'post',
			success : function(r) {
				$('#id').val(r.id);
				$('#jenis_rekanan').val(r.jenis_rekanan).trigger('change');
				$('#kode_rekanan').val(r.kode_rekanan);
				$('#kode_sap').val(r.kode_sap);
				$('#nama_pimpinan').val(r.nama_pimpinan);
				$('#jabatan_pimpinan').val(r.jabatan_pimpinan);
				$('#nama').val(r.nama);
				$('#npwp').val(r.npwp);
				$.each(r.id_kategori,function(v,d){
					$('#id_kategori_rekanan').find('[value="'+d+'"]').prop('selected',true);
				});
				$('#id_kategori_rekanan').trigger('change');
				$('#no_identitas').val(r.no_identitas);
				$('#tanggal_berakhir_identitas').val(r.tanggal_berakhir_identitas);
				$('#id_bentuk_badan_usaha').val(r.id_bentuk_badan_usaha).trigger('change');
				$('#id_status_perusahaan').val(r.id_status_perusahaan).trigger('change');
				$('#id_kualifikasi').val(r.id_kualifikasi).trigger('change');
				$('#id_asosiasi').val(r.id_asosiasi).trigger('change');
				$('#id_unit_daftar').val(r.id_unit_daftar).trigger('change');
				$('#alamat').val(r.alamat);
				$('#id_negara').val(r.id_negara).trigger('change');
				$('#id_provinsi').html(r.opt_provinsi).val(r.id_provinsi).trigger('change');
				$('#nama_provinsi').val(r.nama_provinsi);
				if(r.id_provinsi == '999') {
					$('#nama_provinsi').parent().removeClass('hidden');
				}
				$('#id_kota').html(r.opt_kota).val(r.id_kota).trigger('change');
				$('#nama_kota').val(r.nama_kota);
				if(r.id_kota == '999') {
					$('#nama_kota').parent().removeClass('hidden');
				}

				$('#id_kecamatan').html(r.opt_kecamatan).val(r.id_kecamatan).trigger('change');
				$('#nama_kecamatan').val(r.nama_kecamatan);
				if(r.id_kecamatan == '999') {
					$('#nama_kecamatan').parent().removeClass('hidden');
				}

				$('#id_kelurahan').html(r.opt_kelurahan).val(r.id_kelurahan).trigger('change');
				$('#nama_kelurahan').val(r.nama_kelurahan);
				if(r.id_kelurahan == '999') {
					$('#nama_kelurahan').parent().removeClass('hidden');
				}

				$('#kode_kelompok_rekanan').val(r.kode_kelompok_rekanan).trigger('change');
				$('#kode_bank').val(r.kode_bank).trigger('change');
				$('#nomor_rekening').val(r.nomor_rekening);
				$('#pemilik_rekening').val(r.pemilik_rekening);
				$('#kode_recont').val(r.kode_recont).trigger('change');

				$('#kode_pos').val(r.kode_pos);
				$('#no_telepon').val(r.no_telepon);
				$('#no_fax').val(r.no_fax);
				$('#email').val(r.email);
				$('#nama_cp').val(r.nama_cp);
				$('#hp_cp').val(r.hp_cp);
				$('#email_cp').val(r.email_cp);
				$('#modal-form .modal-footer').html('');
				var footer_text = '';
				var create_info = '';
				var update_info = '';
				if(typeof r['create_by'] != 'undefined' && typeof r['create_at'] != 'undefined') {
					if(r['create_at'] != '0000-00-00 00:00:00') {
						var create_by = r['create_by'] == '' ? 'Unknown' : r['create_by'];
						var create_at = r['create_at'].split(' ');
						var tanggal_c = create_at[0].split('-');
						var waktu_c = create_at[1].split(':');
						var date_c = tanggal_c[2]+'/'+tanggal_c[1]+'/'+tanggal_c[0]+' '+waktu_c[0]+':'+waktu_c[1];
						create_info += '<small>' + lang.dibuat_oleh + ' <strong>' + create_by + ' </strong> @ ' + date_c + '</small>';
					}
				}

				if(typeof r['update_by'] != 'undefined' && typeof r['update_at'] != 'undefined') {
					if(r['update_at'] != '0000-00-00 00:00:00') {
						var update_by = r['update_by'] == '' ? 'Unknown' : r['update_by'];
						var update_at = r['update_at'].split(' ');
						var tanggal_u = update_at[0].split('-');
						var waktu_u = update_at[1].split(':');
						var date_u = tanggal_u[2]+'/'+tanggal_u[1]+'/'+tanggal_u[0]+' '+waktu_u[0]+':'+waktu_u[1];
						update_info += '<small>' + lang.diperbaharui_oleh + ' <strong>' + update_by + ' </strong> @ ' + date_u + '</small>';
					}
				}

				if(create_info || update_info) {
					footer_text += '<div class="w-100">';
					footer_text += create_info;
					footer_text += update_info;
					footer_text += '</div>';
				}

				if(footer_text) {

					$('#modal-form .modal-footer').html(footer_text).removeClass('hidden');

				}
				$('#modal-form').modal();
				is_edit = false;
			}
		});
	}
});

$('#jenis_rekanan').change(function(){
	if($(this).val() == '1') {
		badan_usaha();
	} else {
		perorangan();
	}
});

$('#id_negara').change(function(){
	if(!is_edit) {
		if($(this).val() != '101') {
			$('#id_provinsi').html('<option value=""></option><option value="999">'+lang.lainnya+'</option>').trigger('change');
		} else {
			$('#id_provinsi').html('<option value="0">'+lang.mohon_tunggu+'</option>').trigger('change');
			readonly_ajax = false;
			$.getJSON(base_url + 'ajax/json/wilayah', function(data){
				var konten = '<option value=""></option>';
				$.each(data,function(d,v){
					konten += '<option value="'+v.id+'">'+v.nama+'</option>';
				});
				konten += '<option value="999">'+lang.lainnya+'</option>';
				$('#id_provinsi').html(konten).trigger('change');
				readonly_ajax = true;
			});
		}
	}
});

$('#id_provinsi').change(function(){
	if(!is_edit) {
		if($(this).val() != '' && $(this).val() != '0') {
			if($(this).val() == '999') {
				$('#nama_provinsi').parent().removeClass('hidden');
				$('#nama_provinsi').val('');
				$('#id_kota').html('<option value=""></option><option value="999">'+lang.lainnya+'</option>').trigger('change');
			} else {
				$('#nama_provinsi').parent().addClass('hidden');
				$('#nama_provinsi').val($(this).find(':selected').text());
				$('#id_kota').html('<option value="0">'+lang.mohon_tunggu+'</option>').trigger('change');
				readonly_ajax = false;
				$.getJSON(base_url + 'ajax/json/wilayah/' + $(this).val(), function(data){
					var konten = '<option value=""></option>';
					$.each(data,function(d,v){
						konten += '<option value="'+v.id+'">'+v.nama+'</option>';
					});
					konten += '<option value="999">'+lang.lainnya+'</option>';
					$('#id_kota').html(konten).trigger('change');
					readonly_ajax = true;
				});
			}
		} else {
			$('#nama_provinsi').parent().addClass('hidden');
			$('#nama_provinsi').val($(this).find(':selected').text());
			$('#id_kota').html('<option value=""></option>').trigger('change');
		}
	}
});

$('#id_kota').change(function(){
	if(!is_edit) {
		if($(this).val() != '' && $(this).val() != '0') {
			if($(this).val() == '999') {
				$('#nama_kota').parent().removeClass('hidden');
				$('#nama_kota').val('');
				$('#id_kecamatan').html('<option value=""></option><option value="999">'+lang.lainnya+'</option>').trigger('change');
			} else {
				$('#nama_kota').parent().addClass('hidden');
				$('#nama_kota').val($(this).find(':selected').text());
				$('#id_kecamatan').html('<option value="0">'+lang.mohon_tunggu+'</option>').trigger('change');
				readonly_ajax = false;
				$.getJSON(base_url + 'ajax/json/wilayah/' + $(this).val(), function(data){
					var konten = '<option value=""></option>';
					$.each(data,function(d,v){
						konten += '<option value="'+v.id+'">'+v.nama+'</option>';
					});
					konten += '<option value="999">'+lang.lainnya+'</option>';
					$('#id_kecamatan').html(konten).trigger('change');
					readonly_ajax = true;
				});
			}

		} else {
			$('#nama_kota').parent().addClass('hidden');
			$('#nama_kota').val($(this).find(':selected').text());
			$('#id_kecamatan').html('<option value=""></option>').trigger('change');
		}
	}
});

$('#id_kecamatan').change(function(){
	if(!is_edit) {
		if($(this).val() != '' && $(this).val() != '0') {
			if($(this).val() == '999') {
				$('#nama_kecamatan').parent().removeClass('hidden');
				$('#nama_kecamatan').val('');
				$('#id_kelurahan').html('<option value=""></option><option value="999">'+lang.lainnya+'</option>').trigger('change');
			} else {
				$('#nama_kecamatan').parent().addClass('hidden');
				$('#nama_kecamatan').val($(this).find(':selected').text());
				$('#id_kelurahan').html('<option value="0">'+lang.mohon_tunggu+'</option>').trigger('change');
				readonly_ajax = false;
				$.getJSON(base_url + 'ajax/json/wilayah/' + $(this).val(), function(data){
					var konten = '<option value=""></option>';
					$.each(data,function(d,v){
						konten += '<option value="'+v.id+'">'+v.nama+'</option>';
					});
					konten += '<option value="999">'+lang.lainnya+'</option>';
					$('#id_kelurahan').html(konten).trigger('change');
					readonly_ajax = true;
				});
			}

		} else {
			$('#nama_kecamatan').parent().addClass('hidden');
			$('#nama_kecamatan').val($(this).find(':selected').text());
			$('#id_kelurahan').html('<option value=""></option>').trigger('change');
		}
	}
});

$('#id_kelurahan').change(function(){
	if(!is_edit) {
		if($(this).val() == '999') {
			$('#nama_kelurahan').parent().removeClass('hidden');
			$('#nama_kelurahan').val('');
		} else {
			$('#nama_kelurahan').parent().addClass('hidden');
			$('#nama_kelurahan').val($(this).find(':selected').text());
		}
	}
});

$(document).on('click','.btn-blacklist',function(){
	__id = $(this).attr('data-id');
	cConfirm.open(lang.anda_yakin_memblacklist_data_ini,'blacklist');
});

function blacklist() {
	$.ajax({
		url : base_url + 'manajemen_rekanan/daftar_rekanan/blacklist',
		data : {
			id : __id
		},
		type : 'post',
		success : function(response) {
			cAlert.open(response,'success','refreshData');
		}
	});
}

$(document).on('click','.btn-dokumen',function(){
	__id = $(this).attr('data-id');
	$.get(base_url + 'manajemen_rekanan/checklist_rekanan/detail/' + __id, function(response){
		cInfo.open(lang.detil,response);
	});
});

$(document).on('click','.btn-pengalaman',function(){
	__id = $(this).attr('data-id');
	$.get(base_url + 'manajemen_rekanan/daftar_rekanan/pengalaman/' + __id, function(response){
		cInfo.open(lang.detil,response);
	});
});

$(document).on('click','.btn-spk',function(){
	__id = $(this).attr('data-id');
	$.get(base_url + 'manajemen_rekanan/daftar_rekanan/daftar_spk/' + __id, function(response){
		cInfo.open(lang.detil,response);
	});
});

$(document).on('click','.btn-pulihkan',function(){
	__id = $(this).attr('data-id');
	cConfirm.open(lang.anda_yakin_memulihkan_data_ini,'pulihkan');
});

function pulihkan() {
	$.ajax({
		url : base_url + 'manajemen_rekanan/daftar_rekanan/pulihkan',
		data : {
			id : __id
		},
		type : 'post',
		success : function(response) {
			cAlert.open(response,'success','refreshData');
		}
	});
}

$(document).on('click','.btn-change-password',function(){
	$('#modal-change-password').modal();
	$('#id_vendor').val($(this).attr('data-id'));
	$('#form-change-password')[0].reset();
	$('#form-change-password :input').removeClass('is-invalid');
	$('#form-change-password span.error').remove();
});
</script>