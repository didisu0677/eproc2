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
	table_open('',true,base_url('pengadaan/hps/data'),'tbl_m_hps');
		thead();
			tr();
				th(lang('no'),'text-center','width="30" data-content="id"');
				th(lang('nomor_pengajuan'),'','data-content="nomor_pengajuan" data-link="detail-pengajuan"');
				th(lang('deskripsi'),'','data-content="deskripsi"');
				th(lang('nomor_hps'),'','data-content="nomor_hps"');
				th(lang('tanggal'),'','data-content="tanggal" data-type="daterange"');
				th(lang('total_hps'),'text-right','data-content="total_hps_pembulatan" data-type="currency"');
				th(lang('status'),'','width="150" data-content="status" data-replace="0:'.lang('draf').'|1:'.lang('diproses').'|2:'.lang('disetujui').'|8:'.lang('dikembalikan').'|9:'.lang('ditolak').'"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<?php 
modal_open('modal-form','','modal-xl','data-openCallback="openForm"');
	modal_body();
		form_open(base_url('pengadaan/hps/save'),'post','form');
			col_init(3,9);
			input('hidden','id','id');
			select2(lang('nomor_pengajuan'),'nomor_pengajuan','required');
			input('text',lang('unit_kerja'),'unit_kerja','required','','data-readonly="true" readonly');
			input('text',lang('divisi'),'nama_divisi','required','','data-readonly="true" readonly');
			textarea(lang('deskripsi'),'deskripsi','required','','data-readonly="true" readonly');
			input('text',lang('mata_anggaran'),'mata_anggaran','required','','data-readonly="true" readonly');
			input('text',lang('besar_anggaran'),'besar_anggaran','required','','data-readonly="true" readonly');
			?>
			<div class="form-group row">
				<label class="col-form-label col-sm-3 required" for="usulan_hps"><?php echo lang('usulan_hps'); ?></label>
				<div class="col-sm-9">
					<div class="input-group">
						<input type="text" name="usulan_hps" id="usulan_hps" autocomplete="off" class="form-control" data-validation="required|max-length:25" value="" readonly data-readonly="true">
						<div class="input-group-append">
							<button type="button" class="btn btn-sm btn-success" id="preview-detail"><i class="fa-search"></i> <?php echo lang('detil'); ?></button>
						</div>
					</div>
				</div>
			</div>
			<?php
			input('text',lang('nomor_hps'),'nomor_hps','required|unique');
			input('date',lang('tanggal'),'tanggal','required');
			?>
			<div class="mb-3 table-responsive">
				<table class="table table-bordered table-app table-items" id="detail">
					<thead>
						<tr>
							<th width="150"><?php echo lang('kode_material'); ?></th>
							<th><?php echo lang('deskripsi'); ?></th>
							<th width="150"><?php echo lang('jumlah'); ?></th>
							<th width="100"><?php echo lang('satuan'); ?></th>
							<th width="150"><?php echo lang('harga_satuan'); ?></th>
							<th width="150"><?php echo lang('total'); ?></th>
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot>
						<tr>
							<th colspan="5"><?php echo strtoupper(lang('total_harga')); ?></th> 
							<th><input type="text" name="total_sebelum_ppn" id="total_sebelum_ppn" autocomplete="off" class="form-control text-right" data-readonly="true" readonly /></th>
						</tr>
					</tfoot>
				</table>
			</div>
			<?php
			form_button(lang('simpan'),lang('batal'));
		form_close();
	modal_footer();
modal_close();
?>
<script>
var isEdit = false;
function openForm() {
	var response = response_edit;
	$('#detail tbody').html('');
	if(typeof response.id != 'undefined') {
		isEdit = true;
		$('#nomor_pengajuan').html(response.nomor_pengajuan1).val(response.nomor_pengajuan).trigger('change');
		renderDetail(response.detail);
		$('#persen_ppn').val(cPercent(response.persen_ppn));
		calculate();
		isEdit = false;
	} else {
		view_combo();
	}
}
function view_combo() {
	$.ajax({
		url			: base_url + 'pengadaan/hps/get_combo',
		dataType	: 'json',
		success     : function(response){
			$('#nomor_pengajuan').html(response.nomor_pengajuan);
		}
	});
}
$('#nomor_pengajuan').change(function(){
	$('#deskripsi').val($(this).find(':selected').attr('data-nama_pengadaan'));
	$('#nama_divisi').val($(this).find(':selected').attr('data-nama_divisi'));
	$('#unit_kerja').val($(this).find(':selected').attr('data-unit_kerja'));
	$('#mata_anggaran').val($(this).find(':selected').attr('data-mata_anggaran'));
	$('#besar_anggaran').val($(this).find(':selected').attr('data-besar_anggaran'));
	$('#usulan_hps').val($(this).find(':selected').attr('data-usulan_hps'));
	if(!isEdit && typeof $(this).find(':selected').attr('data-usulan_hps') != 'undefined') {
		$('#detail tbody').html('');
		$.ajax({
			url : base_url + 'pengadaan/hps/sap_detail',
			data : {'nomor_pengajuan':$(this).val()},
			dataType : 'json',
			type : 'POST',
			success : function(r) {
				renderDetail(r);
			}
		});
	}
});
$('#modal-form').on('hidden.bs.modal',function(){
	$('#nomor_pengajuan').html('<option value=""></option>').trigger('change');
});
function renderDetail(r) {
	var index = 1;
	$.each(r, function(k,v){
		var konten = '<tr>' +
						'<td><input type="hidden" name="id_detail['+index+']" value="'+v.id_sap+'">'+v.material_number+'</td>' +
						'<td>'+v.short_text+'</td>' +
						'<td><input type="text" name="quantity['+index+']" autocomplete="off" class="form-control text-right money calc quantity" data-validation="required|number|max:'+v.maksimal+'" aria-label="'+$('#detail').find('th:nth-child(3)').text()+'" value="'+customFormat(v.quantity)+'" /></td>' +
						'<td>'+v.unit_of_measure+'</td>' +
						'<td><input type="text" name="price_unit['+index+']" autocomplete="off" class="form-control text-right money calc price_unit" data-validation="required|number" value="'+customFormat(v.price_unit)+'" /></td>' +
						'<td><input type="text" name="total_value['+index+']" autocomplete="off" class="form-control text-right money calculate total_value" value="'+customFormat(v.total_value)+'" data-readonly="true" readonly /></td>' +
					'</tr>';
		$('#detail tbody').append(konten);
		index++;
	});
	$(".money:not([readonly])").maskMoney({allowNegative: true, thousands:'.', decimal:',', precision: 0});
	calculate();
}
$(document).on('keyup','.calc',function(){
	var q = $(this).closest('tr').find('.quantity').val();
	var p = $(this).closest('tr').find('.price_unit').val();
	var t = moneyToNumber(q) * moneyToNumber(p);
	$(this).closest('tr').find('.total_value').val(customFormat(t));
	calculate();
});
$(document).on('keyup','.calculate',function(){
	calculate();
});
function calculate() {
	var total = 0;
	$('.total_value').each(function(){
		total += moneyToNumber($(this).val());
	});
	$('#total_sebelum_ppn').val(customFormat(total));
}
$('#preview-detail').click(function(){
	if($('#nomor_pengajuan').val() != '') {
		var v = $('#nomor_pengajuan').val();
		$.get(base_url+'pengadaan/pengajuan/detail_sap?no_pengajuan='+v,function(result){
			cInfo.open(lang.detil,result);
		});
	}
});
$(document).on('click','.detail-pengajuan',function(){
	$.get(base_url+'pengadaan/pengajuan/detail?no_pengajuan='+$(this).attr('data-value'),function(result){
		cInfo.open(lang.detil,result);
	});
});
$(document).on('click','.btn-print',function(){
	var id = encodeId($(this).attr('data-id'));
	$.redirect(base_url + 'pengadaan/hps/cetak_hps/' + id, {} , 'get', '_blank');
});
</script>