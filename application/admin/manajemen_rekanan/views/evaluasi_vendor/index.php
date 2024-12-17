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
	table_open('',true,base_url('manajemen_rekanan/evaluasi_vendor/data'),'tbl_evaluasi_vendor');
		thead();
			tr();
				th('checkbox','text-center','width="30" data-content="id"');
				th(lang('nomor'),'','data-content="nomor"');
				th(lang('nomor_pengadaan'),'','data-content="nomor_pengadaan"');
				th(lang('nama_pengadaan'),'','data-content="nama_pengadaan"');
				th(lang('nama_vendor'),'','data-content="nama_vendor"');
				th(lang('nilai_kontrak'),'text-right','data-content="nilai_kontrak" data-type="currency"');
				th(lang('nama_evaluator'),'','data-content="nama_evaluator"');
				th(lang('jabatan'),'','data-content="jabatan"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<?php 
modal_open('modal-form','','modal-xl','data-openCallback="formOpen"');
	modal_body();
		form_open(base_url('manajemen_rekanan/evaluasi_vendor/save'),'post','form');
			col_init(0,12);
			input('hidden','id','id');
			?>
			<div class="main-container">
				<div class="card mb-2">
					<div class="card-header"><?php echo lang('informasi_pengadaan'); ?></div>
					<div class="card-body">
						<table class="table table-bordered table-detail mb-0">
							<tr>
								<th width="200"><?php echo lang('nomor'); ?></th>
								<td><?php input('text',lang('nomor'),'nomor'); ?></td>
							</tr>
							
							<tr>
								<th width="200"><?php echo lang('tanggal'); ?></th>
								<td><?php input('date',lang('tanggal'),'tanggal'); ?></td>
							</tr>
	

							<tr>
								<th width="200"><?php echo lang('nama_pengadaan'); ?></th>
								<td><?php select2('','nomor_pengadaan','nomor_pengadaan'); ?></td>
							</tr>

							<tr>
								<th width="200"><?php echo lang('nama_vendor'); ?></th>
								<td><?php input('text',lang('nama_vendor'),'nama_vendor','','','data-readonly="true" readonly'); ?></td>
								<?php input('hidden',lang('id_vendor'),'id_vendor'); ?>
							</tr>


							<tr>
								<th><?php echo lang('nilai_kontrak'); ?></th>
								<td><?php input('money',lang('nilai_kontrak'),'nilai_kontrak','','','data-readonly="true" readonly'); ?></td>
							</tr>

							<tr>
								<th><?php echo lang('nama_evaluator'); ?></th>
								<td><?php input('text','','nama_evaluator','nama_evaluator'); ?></td>
							</tr>

							<tr>
								<th><?php echo lang('jabatan'); ?></th>
								<td><?php input('text','','jabatan','jabatan'); ?></td>
							</tr>

						</table>
					</div>
				</div>
				<div class="table-responsive mb-2">
					<table class="table table-bordered table-detail table-app">
						<thead>
							<tr>
								<th colspan="2" width="600" class="text-center"><?php echo lang('aspek_yang_dievaluasi'); ?></th>
								<th class="text-center"><?php echo lang('sangat_baik'); ?></th>
								<th class="text-center"><?php echo lang('baik'); ?></th>
								<th class="text-center"><?php echo lang('cukup_baik'); ?></th>
								<th class="text-center"><?php echo lang('kurang_baik'); ?></th>
								<th class="text-center"><?php echo lang('tidak_baik'); ?></th>
							</tr>
						</thead>
						<tbody id="d2">
							<?php $idx = 0 ?>
							<?php foreach($m_evaluasi as $d) { ?>
							<tr>
								<td colspan="2" width= "600"><?php echo $d->nama; ?>
								
									<input type="hidden" name="id_dok[]" value="<?php echo $d->id; ?>">
									<input type="hidden" id ="<?php echo 'nama'. $idx; ?>" name="nama_evaluasi[]" value="<?php echo $d->nama; ?>">

								</td>
								<td class="text-center">						
									<div class="custom-checkbox custom-control">
										<input class="custom-control-input chk" type="checkbox" id="<?php echo 'check_a'. $idx; ?>" name="<?php echo 'check_a['.$idx.']'; ?>" value="<?php echo $idx; ?>">
										<label class="custom-control-label" for="<?php echo 'check_a'. $idx; ?>"></label>
									</div>
								</td>

								<td class="text-center">						
									<div class="custom-checkbox custom-control">
										<input class="custom-control-input chk" type="checkbox" id="<?php echo 'check_b'. $idx; ?>" name="<?php echo 'check_b['. $idx.']'; ?>" value="<?php echo $idx; ?>">
										<label class="custom-control-label" for="<?php echo 'check_b'. $idx; ?>"></label>
									</div>
								</td>

								<td class="text-center">						
									<div class="custom-checkbox custom-control">
										<input class="custom-control-input chk" type="checkbox" id="<?php echo 'check_c'. $idx; ?>" name="<?php echo 'check_c['. $idx.']'; ?>" value="<?php echo $idx; ?>">
										<label class="custom-control-label" for="<?php echo 'check_c'. $idx; ?>"></label>
									</div>
								</td>


								<td class="text-center">						
									<div class="custom-checkbox custom-control">
										<input class="custom-control-input chk" type="checkbox" id="<?php echo 'check_d'. $idx; ?>" name="<?php echo 'check_d['. $idx.']'; ?>" value="<?php echo $idx; ?>">
										<label class="custom-control-label" for="<?php echo 'check_d'. $idx; ?>"></label>
									</div>
								</td>


								<td class="text-center">						
									<div class="custom-checkbox custom-control">
										<input class="custom-control-input chk" type="checkbox" id="<?php echo 'check_e'. $idx; ?>" name="<?php echo 'check_e['. $idx.']'; ?>" value="<?php echo $idx; ?>">
										<label class="custom-control-label" for="<?php echo 'check_e'. $idx; ?>"></label>
									</div>
								</td>
							</tr>
							<?php $idx++ ?>	
							<?php } ?>
						<tr>
							<div id="d3">
							<td width="10"><button type="button" class="btn btn-sm btn-success btn-icon-only btn-add-pendukung"><i class="fa-plus"></i></button></td>
							<td colspan="6"><?php echo lang('lain_lain'); ?></td>						
							</div>
						</tr>
						</tbody>
					</table>
			    </div>
			</div>	
			<?php 
			col_init(2,9);
			?>
			<div class="form-group row">
				<label class="col-form-label col-sm-2"><?php echo lang('rekomendasi_selanjutnya'); ?></label>
				<div class="col-sm-9 col-9">
					<select class="select2 custom-select" id="hasil_rekomendasi" name="hasil_rekomendasi" required>
						<option value="1"><?php echo lang('disarankan_untuk_bisa_menjadi_peserta_pengadaan_selanjutnya_di_Pegadaian');?></option>
						<option value="9"><?php echo lang('tidak_disarankan_untuk_bisa_menjadi_peserta_pengadaan_selanjutnya_di_Pegadaian'); ?></option>
					</select>
			</div>
			</div>	
			<?php
			input('text',lang('keterangan_lain'),'keterangan_lain');
			form_button(lang('simpan'),lang('batal'));

		form_close();
	modal_footer();
modal_close();
modal_open('modal-import',lang('impor'));
	modal_body();
		form_open(base_url('manajemen_rekanan/evaluasi_vendor/import'),'post','form-import');
			col_init(3,9);
			fileupload('File Excel','fileimport','required','data-accept="xls|xlsx"');
			form_button(lang('impor'),lang('batal'));
		form_close();
modal_close();
?>

<script type="text/javascript">
	var pengadaan = {};
	var idx = 1;

	function formOpen() {
	//	$('#form')[0].reset();
		is_edit = true;
		var response = response_edit;
		if(typeof response.id != 'undefined') {
			$('#nomor_pengadaan').html('<option value="'+response.nomor_pengadaan+'">'+response.nomor_pengadaan+ ' | ' +response.nama_pengadaan +'</option>').trigger('change');

			var idx = 0;
			var nama = "";
			var check_a = "";
			$.each(response.aspek_evaluasi,function(e,d){
				nama = '#nama' + idx;
				check_a = '#check_a' + idx;
				check_b = '#check_b' + idx;
				check_c = '#check_c' + idx;
				check_d = '#check_d' + idx;
				check_e = '#check_e' + idx;

				//if(e == '0') {
				//	$('#username').val(d.userid).trigger('change');$(check_a).val(d.sangat_baik);
					$(nama).val(d.nama_evaluasi);
					$(check_a).val(d.sangat_baik);
					$(check_b).val(d.baik);
					$(check_c).val(d.cukup_baik);
					$(check_d).val(d.kurang_baik);
					$(check_e).val(d.tidak_baik);
                    
                    if($(check_a).val()==1) {
						$(check_a).prop( "checked", true );
					}

					if($(check_b).val()==1) {
						$(check_b).prop( "checked", true );
					}

					if($(check_c).val()==1) {
						$(check_c).prop( "checked", true );
					}

					if($(check_d).val()==1) {
						$(check_d).prop( "checked", true );
					}

					if($(check_e).val()==1) {
						$(check_e).prop( "checked", true );
					}

				idx++;
			});

			var idx2 = 0;
			var checklain_a = "" ;
			var checklain_b = "" ;
			var checklain_c = "" ;
			var checklain_d = "" ;
			var checklain_e = "" ;
			$.each(response.lain,function(e,d){
				$('.btn-remove').closest('tr').remove();

				checklain_a = "" ;
				checklain_b = "" ;
				checklain_c = "" ;
				checklain_d = "" ;
				checklain_e = "" ;

				if(d.sangat_baik ==1) {
					checklain_a = "checked" ;
				}
				if(d.baik ==1) {
					checklain_b = "checked" ;
				}
				if(d.cukup_baik ==1) {
					checklain_c = "checked" ;
				}
				if(d.kurang_baik ==1) {
					checklain_d = "checked" ;
				}
				if(d.tidak_baik ==1) {
					checklain_e = "checked" ;
				}

			var konten = '<tr>'
				+ '<td><button type="button" class="btn btn-sm btn-danger btn-remove btn-icon-only"><i class="fa-times"></i></button></td>'
				+ '<td><input type="text" class="form-control" name="evaluasi_lain[]" value ='+d.nama_evaluasi+' autocomplete="off" data-validation="required"></td>'

				+ '<td class="text-center">'						
				+ '<div class="custom-checkbox custom-control">'
				+ '<input class="custom-control-input chk" type="checkbox" id="checklain_a'+idx2+'" name="checklain_a[]" value="'+idx2+'" '+checklain_a+'>'
				+ '<label class="custom-control-label" for="checklain_a'+idx2+'"></label></div>'
				+ '</td>'

				+ '<td class="text-center">'	
				+ '<div class="custom-checkbox custom-control">'
				+ '<input class="custom-control-input chk" type="checkbox" id="checklain_b'+idx2+'" name="checklain_b[]" value="'+idx2+'" '+checklain_b+'>'
				+ '<label class="custom-control-label" for="checklain_b'+idx2+'"></label></div>'
				+ '</td>'

				+ '<td class="text-center">'	
				+ '<div class="custom-checkbox custom-control">'
				+ '<input class="custom-control-input chk" type="checkbox" id="checklain_c'+ idx2+'" name="checklain_c[]" value="'+idx2+'" '+checklain_c+'>'
				+ '<label class="custom-control-label" for="checklain_c'+idx2+'"></label></div>'
				+ '</td>'

				+ '<td class="text-center">'	
				+ '<div class="custom-checkbox custom-control">'
				+ '<input class="custom-control-input chk" type="checkbox" id="checklain_d'+idx2+'" name="checklain_d[]" value="'+idx2+'" '+checklain_d+'>'
				+ '<label class="custom-control-label" for="checklain_d'+idx2+'"></label></div>'
				+ '</td>'

				+ '<td class="text-center">'	
				+ '<div class="custom-checkbox custom-control">'
				+ '<input class="custom-control-input chk" type="checkbox" id="checklain_e'+idx2+'" name="checklain_e[]" value="'+idx2+'" '+checklain_e+'>'
				+ '<label class="custom-control-label" for="checklain_e'+idx2+'"></label></div>'
				+ '</td>'


			+ '</tr>';

			$('#d2').append(konten);
			$('#d2 tr').last().find('select').select2({
				placeholder: '',
				minimumResultsForSearch: Infinity,
				dropdownParent : $('#d1 tr').last().find('select').parent(),
				width: '100%'
			});
			idx2++;
			});


		} else {
			view_combo();
		}		
		is_edit = false;
	}

	function view_combo() {
		$.ajax({
			url			: base_url + 'manajemen_rekanan/evaluasi_vendor/get_combo',
			dataType	: 'json',
			success     : function(response){
				pengadaan 	= response.pengadaan;
				var konten 	= '<option value=""></option>';
				$.each(pengadaan,function(k,v){
					konten += '<option value="'+v.nomor_pengadaan+'">'+v.nomor_pengadaan+' | '+v.nama_pengadaan+'</option>';
				});
				$('#nomor_pengadaan').html(konten).trigger('change');
			}
		});
	}

	$('#nomor_pengadaan').change(function(){
		if(typeof pengadaan[$(this).val()] != 'undefined') {
			var p = pengadaan[$(this).val()];
			$('#nama_vendor').val(p.nama_vendor);
			$('#id_vendor').val(p.id_vendor);
			$('#nilai_kontrak').val(p.nilai_kontrak);
		}
	});

	$('.btn-add-pendukung').click(function(){
	var konten = '<tr>'
		+ '<td><button type="button" class="btn btn-sm btn-danger btn-remove btn-icon-only"><i class="fa-times"></i></button></td>'
		+ '<td><input type="text" class="form-control" name="evaluasi_lain[]" autocomplete="off" data-validation="required"></td>'

		+ '<td class="text-center">'						
		+ '<div class="custom-checkbox custom-control">'
		+ '<input class="custom-control-input chk" type="checkbox" id="checklain_a'+idx+'" name="checklain_a[]" value="'+idx+'">'
		+ '<label class="custom-control-label" for="checklain_a'+idx+'"></label></div>'
		+ '</td>'

		+ '<td class="text-center">'	
		+ '<div class="custom-checkbox custom-control">'
		+ '<input class="custom-control-input chk" type="checkbox" id="checklain_b'+idx+'" name="checklain_b[]" value="'+idx+'">'
		+ '<label class="custom-control-label" for="checklain_b'+idx+'"></label></div>'
		+ '</td>'

		+ '<td class="text-center">'	
		+ '<div class="custom-checkbox custom-control">'
		+ '<input class="custom-control-input chk" type="checkbox" id="checklain_c'+ idx+'" name="checklain_c[]" value="'+idx+'">'
		+ '<label class="custom-control-label" for="checklain_c'+idx+'"></label></div>'
		+ '</td>'

		+ '<td class="text-center">'	
		+ '<div class="custom-checkbox custom-control">'
		+ '<input class="custom-control-input chk" type="checkbox" id="checklain_d'+idx+'" name="checklain_d[]" value="'+idx+'">'
		+ '<label class="custom-control-label" for="checklain_d'+idx+'"></label></div>'
		+ '</td>'

		+ '<td class="text-center">'	
		+ '<div class="custom-checkbox custom-control">'
		+ '<input class="custom-control-input chk" type="checkbox" id="checklain_e'+idx+'" name="checklain_e[]" value="'+idx+'">'
		+ '<label class="custom-control-label" for="checklain_e'+idx+'"></label></div>'
		+ '</td>'


	+ '</tr>';

	$('#d2').append(konten);
	$('#d2 tr').last().find('select').select2({
		placeholder: '',
		minimumResultsForSearch: Infinity,
		dropdownParent : $('#d1 tr').last().find('select').parent(),
		width: '100%'
	});

	idx++;
});

$(document).on('click','.btn-remove',function(){
	$(this).closest('tr').remove();
});

$(document).on('click','.btn-print',function(){
	var id = encodeId($(this).attr('data-id'));
	$.redirect(base_url + 'manajemen_rekanan/evaluasi_vendor/cetak_evaluasi/' + id, {} , 'get', '_blank');
});

function detail_callback(id){
	$.get(base_url+'manajemen_rekanan/evaluasi_vendor/detail/'+id,function(result){
		cInfo.open(lang.detil,result);
	});
}

</script>