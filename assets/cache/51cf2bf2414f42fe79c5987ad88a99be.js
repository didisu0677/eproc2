
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

