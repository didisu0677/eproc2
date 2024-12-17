    
	function getData() {
		cLoader.open(lang.memuat_data + '...');
		var page = base_url + 'pengadaan/lap_hasil_pengadaan/data';
		page 	+= '/'+$('#filter_tahun').val();
		page 	+= '/'+$('#user').val();
		page 	+= '/'+$('#panitia').val();
		page 	+= '/'+$('#nilai_hps').val();
		page 	+= '/'+$('#metode_pengadaan').val();

		$.ajax({
			url 	: page,
			data 	: {},
			type	: 'get',
			dataType: 'json',
			success	: function(response) {
				$('.table-app tbody').html(response.table);
				cLoader.close();
				fixedTable();


				$('#result tbody').html(response.items);
			}
		});
	}
	$(function(){
		getData();
	});

	$('.btn-export').click(function(){
		
		var currentdate = new Date(); 
		var datetime = currentdate.getDate() + "/"
		                + (currentdate.getMonth()+1)  + "/" 
		                + currentdate.getFullYear() + " @ "  
		                + currentdate.getHours() + ":"  
		                + currentdate.getMinutes() + ":" 
		                + currentdate.getSeconds();
		

		var table	= '<table>';
		table += '<tr><td colspan="1">Laporan Hasil Pengadaan</td></tr>';
		table += '<tr><td colspan="1"> Tahun </td><td colspan="25">: '+$('#filter_tahun').val()+'</td></tr>';
		table += '<tr><td colspan="1"> Print date </td><td colspan="25">: '+datetime+'</td></tr>';

		
		table += '</table><br />';
		table += '<table border="1">';
		table += $('.table-download').html();
		table += '</table>';
		var target = table;
		window.open('data:application/vnd.ms-excel,' + encodeURIComponent(target));
		$('.bg-grey-1,.bg-grey-2.bg-grey-2-1,.bg-grey-2-2,.bg-grey-3').each(function(){
			$(this).removeAttr('bgcolor');
		});
	});
	
	$('#btn-show').click(function(){
    	getData();
		return false;
	});

	$('.btn-print').click(function(){
		var page = base_url + 'pengadaan/lap_hasil_pengadaan/print_data';
		page 	+= '/'+$('#filter_tahun').val();
		page 	+= '/'+$('#user').val();
		page 	+= '/'+$('#panitia').val();
		page 	+= '/'+$('#nilai_hps').val();
		page 	+= '/'+$('#metode_pengadaan').val();

	

		window.open(page, '_blank');


		return false;
	});
