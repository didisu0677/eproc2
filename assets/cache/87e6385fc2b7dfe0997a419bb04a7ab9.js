
	function getData() {
		cLoader.open(lang.memuat_data + '...');
		$.ajax({
			url 	: base_url + 'settings/bidang_usaha/data',
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
