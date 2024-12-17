<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div class="content-body">
	<div class="main-container">
	<div class="row">
		<div class="col-sm-3 col-12 mb-2">
			<div class="sticky-top">
				<?php
					card_open('Filter','mb-2');
						form_open(base_url('manajemen_rekanan/laporan_drm/view'),'post','form-laporan');
							col_init(12,12);
							?>
							<?php
							select2('kanwil','id_unit_daftar','required|infinity|all',$kanwil,'id','unit','all');
							form_button('Filter',false);
						form_close();
					card_close();
					card_open('Export Laporan');
						form_open(base_url('manajemen_rekanan/laporan_drm/view'),'post','form-export'); ?>
							<div class="form-group row">
								<div class="col-6">
									<input type="hidden" id="token" value="<?php csrf_token(false); ?>">
									<select class="custom-select select2 infinity" id="export-to">
										<option value="excel">Excel</option>
										<option value="pdf">PDF</option>
									</select>
								</div>
								<div class="col-6">
									<button type="submit" class="btn btn-sm btn-info btn-block"><i class="fa-upload"></i>Export</button>
								</div>
							</div> 
						<?php form_close();
					card_close();
				?>
			</div>
		</div>
		<div class="col-sm-9 col-12">
			<div class="card">
				<div class="card-header pl-3 pr-3">
					<ul class="nav nav-pills card-header-pills">
						<li class="nav-item">
							<a class="nav-link active" href="#overall" data-toggle="pill" role="tab" aria-controls="pills-overall" aria-selected="true">Total</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#detail" data-toggle="pill" role="tab" aria-controls="pills-detail" aria-selected="true">Detil</a>
						</li>
					</ul>
				</div>
				<div class="card-body tab-content">
					<div class="table-responsive tab-pane fade active show" id="overall">
						<div class="row mr-0 ml-0">
							<div class="col-sm-8 pl-0 pr-0 pr-sm-2">
							<?php
								table_open('table table-bordered table-app table-hover');
									thead();
										tr();
											th('Kantor Wilayah');
											th('Jumlah','text-center','width="100"');
									tbody('result-overall');
										tr();
											td('Silahkan filter laporan terlebih dahulu','text-left','colspan="2"');
								table_close();
							?>							
							</div>
							<div class="col-sm-4 pl-0 pr-0 pl-sm-2">
								<canvas id="chart"></canvas>
							</div>
						</div>
					</div>
					<div class="table-responsive tab-pane fade" id="detail">
					<?php
						table_open('table table-bordered table-app table-hover');
							thead();
								tr();
									th('Nama Vendor');
									th('Alamat');
									th('Jenis');
									th('Kategori');
							tbody('result');
								tr();
									td('Silahkan filter laporan terlebih dahulu','text-left','colspan="11"');
						table_close();
					?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="<?php echo base_url('assets/js/Chart.bundle.min.js'); ?>"></script>
<script type="text/javascript">
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
</script>