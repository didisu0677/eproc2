

$('#id_negara').change(function(){

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

});

$('#id_provinsi').change(function(){

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

});

$('#id_kota').change(function(){

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

});

$('#id_kecamatan').change(function(){

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

});

$('#id_kelurahan').change(function(){

	if($(this).val() == '999') {

		$('#nama_kelurahan').parent().removeClass('hidden');

		$('#nama_kelurahan').val('');

	} else {

		$('#nama_kelurahan').parent().addClass('hidden');

		$('#nama_kelurahan').val($(this).find(':selected').text());

	}

});

$(document).on('click','.btn-remove',function(){

	$(this).closest('.form-group').remove();

});

