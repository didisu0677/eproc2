<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<div class="float-right">
		<?php if($menu_access['access_input']) { ?>
			<button type="button" class="btn btn-primary btn-sm btn-input" data-id="0"><i class="fa-plus"></i><?php echo lang('tambah'); ?></button>
		<?php } ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body">
	<?php
	table_open('',true,'','','data-table="tbl_m_klasifikasi"');
		thead();
			tr();
				th(lang('kode'),'','width="170"');
				th(lang('klasifikasi'));
				th(lang('pilihan'),'text-center','width="100px"');
				th('&nbsp;','','width="30"');
		tbody();
	table_close();
	?>
</div>
<?php 
	modal_open('modal-form');
		modal_body();
			form_open(base_url('settings/klasifikasi/save'),'post','form');
				col_init(3,9);
				input('hidden','id','id');
				input('text',lang('kode'),'kode','required|unique');
				select2(lang('sub_dari'),'parent_id');
				input('text',lang('klasifikasi'),'klasifikasi','required');
				toggle(lang('pilihan'),'pilihan');
				form_button(lang('simpan'),lang('batal'));
			form_close();
		modal_footer();
	modal_close();
?>
<script type="text/javascript">
	function getData() {
		cLoader.open(lang.memuat_data + '...');
		$.ajax({
			url 	: base_url + 'settings/klasifikasi/data',
			data 	: {},
			type	: 'get',
			dataType: 'json',
			success	: function(response) {
				$('.table-app tbody').html(response.table);
				$('#parent_id').html(response.option);
				cLoader.close();
				cek_autocode();
				fixedTable();
				var item_act	= {};
				if($('.table-app tbody .btn-input').length > 0) {
					item_act['active'] 		= {name : lang.aktif, icon : "toggle-on"};
					item_act['inactive'] 	= {name : lang.tidak_aktif, icon : "toggle-off"};
					item_act["sep2"] 		= "---------";
					item_act['edit'] 		= {name : lang.ubah, icon : "edit"};					
				}
				if($('.table-app tbody .btn-input').length > 0) {
					item_act['delete'] 		= {name : lang.hapus, icon : "delete"};					
				}
				var act_count = 0;
				for (var c in item_act) {
					act_count = act_count + 1;
				}
				if(act_count > 0) {
					$.contextMenu({
				        selector: '.table-app tbody tr', 
				        callback: function(key, options) {
				        	if($(this).find('[data-key="'+key+'"]').length > 0) {
					        	if(typeof $(this).find('[data-key="'+key+'"]').attr('href') != 'undefined') {
					        		window.location = $(this).find('[data-key="'+key+'"]').attr('href');
					        	} else {
						        	$(this).find('[data-key="'+key+'"]').trigger('click');
						        }
						    } else if(key == 'active') {
						    	var data_id = $(this).find('.btn-input').attr('data-id');
						    	if(typeof active_inactive  === 'function') {
						    		active_inactive(data_id,'1');
						    	} else {
						    		cAlert.open(lang.fungsi_aktif_tidak_tersedia);
						    	}
						    } else if(key == 'inactive') {
						    	var data_id = $(this).find('.btn-input').attr('data-id');
						    	if(typeof active_inactive  === 'function') {
						    		active_inactive(data_id,'0');
						    	} else {
						    		cAlert.open(lang.fungsi_tidak_aktif_tidak_tersedia);
						    	}
						    }
				        },
				        items: item_act
				    });
				}
			}
		});
	}
	$(function(){
		getData();
	});
	$(document).on('dblclick','.table-app tbody td .badge',function(){
		if($(this).closest('tr').find('.btn-input').length == 1) {
			var badge_status 	= '0';
			var data_id 		= $(this).closest('tr').find('.btn-input').attr('data-id');
			if( $(this).hasClass('badge-danger') ) {
				badge_status = '1';
			}
			active_inactive(data_id,badge_status);
		}
	});
</script>