
var myChart;
var serialize_color = [
	'#404E67',
	'#22C2DC',
	'#ff6384',
	'#ff9f40',
	'#ffcd56',
	'#4bc0c0',
	'#9966ff',
	'#36a2eb',
	'#848484',
	'#e8b892',
	'#bcefa0',
	'#4dc9f6',
	'#a0e4ef',
	'#c9cbcf',
	'#00A5A8',
	'#10C888'
];
$('#form-laporan').submit(function(e){
	e.preventDefault();
	if(validation('form-laporan')) {
		$.ajax({
			url 		: $(this).attr('action'),
			data 		: $(this).serialize(),
			type 		: 'post',
			dataType	: 'json',
			success 	: function(response) {
				var konten 				= '';
				var konten_overall 		= '';
				var data_pie 			= [];
				var color_pie 			= [];
				var label_chart 		= [];
				var i = 0;
				var n = 0;
				var jumlah = 0;
				var nmjumlah = '';
				var nmjumlah1 = '';
					$.each(response.result,function(k,v){						
						jumlah = 0;
						nmjumlah1 = 'jumlah' + i ;
						$.each(response.jumlah,function(r,z){
							if(k==r){
								nmjumlah1 = z;
							}
						n++;	
						});	


					konten += '<tr>';
					konten += '<th colspan="11" style="background: #f9f9f9; color: #484848;">'+k+'</th>';
					konten += '</tr>';
					konten_overall += '<tr>';
					konten_overall += '<td>'+k+'</td>';
					konten_overall += '<td class="text-center">'+nmjumlah1+'</td>';
					konten_overall += '</tr>';
					data_pie.push(parseInt(nmjumlah1));
					color_pie.push(serialize_color[i]);
					label_chart.push(k);
					$.each(v,function(x,y){
						konten += '<tr>';
						konten += '<td class="sub-1">'+y.nama+' </td>';
						konten += '<td>'+y.alamat+'</td>';
						konten += '<td>'+y.jenis+'</td>';
						konten += '<td>'+y.kategori+'</td>';
						konten += '</tr>';
					});

					i++;
				});
				$('.result-overall').html(konten_overall);
				$('.result').html(konten);
				myChart.data = {
					datasets: [{
						data: data_pie,
						backgroundColor: color_pie,
						label: 'Kantor Wilayah'
					}],
					labels: label_chart,
				};
				myChart.update();
				setTimeout(function(){
					$.ajax({
						url 	: base_url + 'manajemen_rekanan/save_image',
						type 	: 'post',
						data 	: {
							image : myChart.toBase64Image(),
							tipe : 'laporan_drm'
						},
                        success : function() {}
					});
				},1000);
			}
		});
	}
});
$('#form-export').submit(function(e){
	e.preventDefault();
	if(validation('form-laporan')) {
		var params = {
			'id_unit_daftar': $('#id_unit_daftar').val(),
			'csrf_token' 	: $('#token').val(),
			'tipe' 			: $('#export-to').val(),
			'status_name'	: $('#status option:selected').text()
		};
		var url = $(this).attr('action');
		$.redirect(url, params, "POST", "_blank"); 
	}
});
$(document).ready(function(){
	var ctxPie = document.getElementById('chart').getContext('2d');
	myChart = new Chart(ctxPie, {
		type: 'pie',
		options: {
			maintainAspectRatio: false,
			responsive: true,
			legend: {
				display: true,
				position: 'right',
				labels: {
					boxWidth: 15,
					generateLabels: function(chart) {
						var data = chart.data;
						if (data.labels.length && data.datasets.length) {
							return data.labels.map(function(label, i) {
								var meta = chart.getDatasetMeta(0);
								var ds = data.datasets[0];
								var arc = meta.data[i];
								var custom = arc && arc.custom || {};
								var getValueAtIndexOrDefault = Chart.helpers.getValueAtIndexOrDefault;
								var arcOpts = chart.options.elements.arc;
								var fill = custom.backgroundColor ? custom.backgroundColor : getValueAtIndexOrDefault(ds.backgroundColor, i, arcOpts.backgroundColor);
								var stroke = custom.borderColor ? custom.borderColor : getValueAtIndexOrDefault(ds.borderColor, i, arcOpts.borderColor);
								var bw = custom.borderWidth ? custom.borderWidth : getValueAtIndexOrDefault(ds.borderWidth, i, arcOpts.borderWidth);

								var value = chart.config.data.datasets[arc._datasetIndex].data[arc._index];

								return {
									text: label + " : " + value,
									fillStyle: fill,
									strokeStyle: stroke,
									lineWidth: bw,
									hidden: isNaN(ds.data[i]) || meta.data[i].hidden,
									index: i
								};
							});
						} else {
							return [];
						}
					}
				}
			}
		}
	});
});
