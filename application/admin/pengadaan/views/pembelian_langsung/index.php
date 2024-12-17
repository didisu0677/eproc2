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
	table_open('',true,base_url('pengadaan/pembelian_langsung/data'),'tbl_pengadaan_langsung');
		thead();
			tr();
				th(lang('no'),'text-center','width="30" data-content="id"');
				th(lang('nomor_pengadaan'),'','data-content="nomor_pengadaan"');
				th(lang('nama_pengadaan'),'','data-content="nama_pengadaan"');
				th(lang('tanggal_pengadaan'),'','data-content="tanggal_pengadaan" data-type="daterange"');
				th(lang('total_pengadaan'),'text-right','data-content="total_pengadaan" data-type="currency"');
				th('&nbsp;','','width="30" data-content="action_button"');
	table_close();
	?>
</div>
<?php 
modal_open('modal-form','','modal-xl','data-openCallback="init_form"');
	modal_body();
		form_open(base_url('pengadaan/pembelian_langsung/save'),'post','form','data-trigger="cek_detail"');
			col_init(3,9);
			input('hidden','id','id');
			label(strtoupper(lang('informasi_pengadaan')));
			input('text',lang('nomor_pengadaan'),'nomor_pengadaan','required|unique');
			input('text',lang('nama_pengadaan'),'nama_pengadaan','required');
			input('date',lang('tanggal_pengadaan'),'tanggal_pengadaan','required');
			label(strtoupper(lang('informasi_vendor')));
			input('text',lang('nama'),'nama_vendor','required');
			input('text',lang('alamat'),'alamat_vendor','required');
			input('text',lang('npwp'),'npwp_vendor');
			label(strtoupper(lang('detil_pembelian')));
			?>
			<div class="table-responsive">
				<table class="table table-bordered table-app mb-3" id="table-detail">
					<thead>
						<tr>
							<th><button type="button" class="btn btn-success btn-icon-only btn-sm" id="btn-add-detail"><i class="fa-plus"></i></button></th>
							<th><?php echo lang('deskripsi'); ?></th>
							<th><?php echo lang('satuan'); ?></th>
							<th><?php echo lang('harga'); ?></th>
							<th><?php echo lang('jumlah'); ?></th>
							<th><?php echo lang('total'); ?></th>
						</tr>
					</thead>
					<tbody></tbody>
					<tfoot>
						<tr>
							<th colspan="5" class="text-right"><?php echo strtoupper(lang('total')); ?></th>
							<th id="total-harga" class="text-right"></th>
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
<script type="text/javascript">
$('#btn-add-detail').click(function(){
	var konten = '<tr>' +
		'<td><button type="button" class="btn btn-sm btn-danger btn-icon-only btn-remove"><i class="fa-times"></i></button></td>' +
		'<td><input type="text" autocomplete="off" name="deskripsi[]" class="form-control deskripsi" data-validation="required" /></td>' +
		'<td><input type="text" autocomplete="off" name="satuan[]" class="form-control satuan" /></td>' +
		'<td><input type="text" autocomplete="off" name="harga[]" class="form-control text-right harga money" data-validation="required" /></td>' +
		'<td><input type="text" autocomplete="off" name="jumlah[]" class="form-control text-right jumlah" data-validation="required" /></td>' +
		'<td><input type="text" autocomplete="off" name="total[]" class="form-control text-right total" disabled /></td>' +
	'</tr>';
	$('#table-detail tbody').append(konten);
	$(".money:not([readonly])").maskMoney({allowNegative: true, thousands:'.', decimal:',', precision: 0});
});
$(document).on('click','.btn-remove',function(){
	$(this).closest('tr').remove();
	calculate();
});
$(document).on('keyup','.harga,.jumlah',function(){
	var harga = moneyToNumber($(this).closest('tr').find('.harga').val());
	var jumlah = toNumber($(this).closest('tr').find('.jumlah').val());
	var total = harga * jumlah;
	$(this).closest('tr').find('.total').val(customFormat(total));
	calculate();
});
$(document).on('click','.btn-print',function(){
	var id = encodeId($(this).attr('data-id'));
	window.open(base_url + 'pengadaan/pembelian_langsung/print/' + id, '_blank');
});
function calculate() {
	var total = 0;
	$('.total').each(function(){
		total += moneyToNumber($(this).val());
	});
	$('#total-harga').text(customFormat(total));
}
function cek_detail() {
	var ret = false;
	if($('.jumlah').length == 0) {
		$('#btn-add-detail').trigger('click');
	} else ret = true;
	return ret;
}
function init_form() {
	$('#table-detail tbody').html('');
	var res = response_edit;
	if(typeof res.id != 'undefined') {
		$.each(res.detail,function(k,v){
			$('#btn-add-detail').trigger('click');
			var last = $('#table-detail tbody tr').last();
			last.find('.deskripsi').val(v.deskripsi);
			last.find('.satuan').val(v.satuan);
			last.find('.jumlah').val(v.jumlah);
			last.find('.harga').val(customFormat(v.harga)).trigger('keyup');
		});
	}
}
</script>