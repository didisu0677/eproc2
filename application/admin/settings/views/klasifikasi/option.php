<?php 
option();
foreach($klasifikasi[0] as $m0) {
	option($m0->id,$m0->klasifikasi);
	foreach($klasifikasi[$m0->id] as $m1) {
		option($m1->id,'&nbsp; |-----'.$m1->klasifikasi);
	}
}
?>