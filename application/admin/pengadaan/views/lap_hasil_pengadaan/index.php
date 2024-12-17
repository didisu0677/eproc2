<div class="content-header">
	<div class="main-container position-relative">
		<div class="header-info">
			<div class="content-title"><?php echo $title; ?></div>
			<?php echo breadcrumb(); ?>
		</div>
		
		<div class="float-right">
						    		
    		
    	</div>
		<div class="clearfix"></div>			
	</div>
</div>

<div class="content-body">
<div class="main-container">
	<div class="row">
		<div class="col-sm-12 col-12 mb-2">
			<div class="sticky-top">
				<?php
					card_open('Filter','mb-2');
					col_init(12,12); ?>

					<div class="row">
							<div class="col-sm-6">
								<div class="form-group row">
									<label class="col-sm-3 col-form-label" for="tahun"><?php echo lang('tahun'); ?></label>
									<div class="col-sm-9">
										<select class="select2 infinity custom-select" id="filter_tahun">
											<option value=""></option>
											<?php for($i = date('Y'); $i >= date('Y')-1; $i--){ ?>
							                <option value="<?php echo $i; ?>"<?php if($i == date('Y')) echo ' selected'; ?>><?php echo $i; ?></option>
							                <?php } ?>
										</select>
									</div>
								</div>
							
								<div class="form-group row">
									<label class="col-sm-3 col-form-label" for="user"><?php echo lang('user'); ?></label>
									<div class="col-sm-9">
										<select class="select2 custom-select" id="user">
										<option value=0></option>	
										<?php foreach ($user as $ma){ ?>								
											<option value="<?php echo $ma['id'] ?>"><?php echo $ma['divisi']; ?></option>
										<?php } ?>

										</select>
									</div>
								</div>

								<div class="form-group row">
									<label class="col-sm-3 col-form-label" for="panitia"><?php echo lang('panitia'); ?></label>
									<div class="col-sm-9">
										<select class="select2 custom-select" id="panitia">
											<option value=0></option>
											<?php foreach ($panitia as $ma){ ?>
												<option value="<?php echo $ma['id'] ?>"><?php echo $ma['deskripsi']; ?></option>
											<?php } ?>

										</select>
									</div>
								</div>


							</div>	

							<div class="col-sm-6">
								<div class="form-group row">
									<label class="col-sm-3 col-form-label" for="hps"><?php echo lang('nilai_hps'); ?></label>
									<div class="col-sm-9">
										<input type="text" class ="form-control money text-right" name="nilai_hps" id ="nilai_hps" value=0>
									</div>
								</div>

								<div class="form-group row">
									<label class="col-sm-3 col-form-label" for="hps"><?php echo lang('metode_pengadaan'); ?></label>
									<div class="col-sm-9">
										<select class="select2 custom-select" id="metode_pengadaan">
											<option value=0></option>
											<?php foreach ($metode_pengadaan as $ma){ ?>
												<option value="<?php echo $ma['id'] ?>"><?php echo $ma['metode_pengadaan']; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
							</div>	


					<div class="col-12">
					<a class="btn btn-xs btn-info" id="btn-show"><?php echo lang('view_report'); ?></a>   <a class="btn btn-info btn-xs btn-export"><i class="fa fa-download"></i><?php echo lang('export'); ?></a>

					<a class="btn btn-info btn-xs btn-print"><i class="fa fa-print"></i><?php echo lang('cetak'); ?></a>
					<?php
					card_close();

				?>
			</div>
			</div>
				<table id = "result" class="table table-bordered table-detail mb-0 table-download">

	<tbody></tbody>
	</table>
		</div>

	</div>	
</div>

<script type="text/javascript">    
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
</script>