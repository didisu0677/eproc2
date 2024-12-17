<?php
	card_open(lang('data_rekanan'),'mb-2');
		table_open('table table-bordered table-detail');
			tr();
				th(lang('kode_rekanan'),'','width="200"');
				td($kode_rekanan);
			tr();
				th(lang('nama_rekanan'));
				td($nama_rekanan);
			tr();
				th(lang('alamat'));
				td($alamat);
		table_close();
	card_close();
	card_open(lang('surat_peringatan'),'mb-2');
		table_open('table table-bordered table-detail');
?>
		<thead>
			<tr>
				<th width="10"><?php echo lang('no'); ?></th>
				<th><?php echo lang('nomor'); ?></th>
				<th><?php echo lang('jenis'); ?></th>
				<th><?php echo lang('tanggal'); ?></th>
				<th><?php echo lang('catatan'); ?></th>
				<th><?php echo lang('cetak'); ?></th>
				<th><?php echo lang('lampiran'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php $i =0;?>
			<?php foreach($detail as $k) { ?>
			<?php $i ++;?>
			<tr>
				<td class="text-center"><?php echo $i; ?></td>
				<td><?php echo $k->nomor; ?></td>
				<td><?php echo $k->jenis; ?></td>
				<td><?php echo $k->tanggal_mulai; ?></td>
				<td><?php echo $k->catatan; ?></td>
				<td><?php echo '[ <a href="'.base_url('manajemen_rekanan/sp_rekanan/cetak_sp/'.encode_id($k->id)).'" target="_blank">'.lang('lihat_detil').'</a> ]';?></td>
				<td><?php echo '[ <a href="'.base_url('assets/uploads/rekanan/'.$k->id_vendor. '/' . $k->file).'" target="_blank">'.$k->file.'</a> ]';?></td>				
			</tr>
			<?php } ?>
		</tbody>
<?php 

		table_close();
	card_close();
?>