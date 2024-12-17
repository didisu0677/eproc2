<table class="table table-bordered table-detail mb-2">
	<tr>
		<th><?php echo lang('rekanan'); ?></th>
		<th><?php echo lang('_penawaran'); ?></th>
	</tr>
	<tr>
		<th colspan="2"><?php echo lang('penawaran_awal'); ?></th></th>
	</tr>
	<?php foreach($vendor as $k => $v) { ?>
	<tr>
		<td><?php echo $v['nama_vendor']; ?></td>
		<td<?php if($k == 0) echo ' class="bg-success text-white font-weight-bold"'; ?>><?php echo custom_format($v['nilai_total_penawaran']); ?></td>
	</tr>
	<?php } for($i=1; $i <= $max_sesi; $i++) { ?>
	<tr>
		<th colspan="2"><?php echo lang('sesi').' '.$i; ?></th></th>
	</tr>
	<?php foreach($vendor as $v) { ?>
	<tr>
		<td><?php echo $v['nama_vendor']; ?></td>
		<td<?php if(isset($lelang[$i][$v['id_vendor']]) && $lelang[$i][$v['id_vendor']] && $lelang[$i][$v['id_vendor']] == $lelang[$i]['min']) echo ' class="bg-success text-white font-weight-bold"'; ?>><?php if(isset($lelang[$i][$v['id_vendor']]) && $lelang[$i][$v['id_vendor']]) {
			 echo '<a href="'.base_url('pengadaan/klarifikasi_negosiasi/detail_monitoring?i='.$lelang[$i]['id_detail'][$v['id_vendor']]).'" class="cInfo" style="color: #484848;">'.custom_format($lelang[$i][$v['id_vendor']]).'</a>';
			 } else echo '-'; ?></td>
	</tr>
	<?php }} ?>
</table>