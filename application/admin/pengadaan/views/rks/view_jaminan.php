<?php 
if($jaminan_penawaran || $jaminan_pelaksanaan || $jaminan_pemeliharaan) {
    echo '<p></p>';
    echo '<h3>JAMINAN PENGADAAN</h3>';
    if($jaminan_penawaran) {
        echo '<p><strong>Jaminan Penawaran</strong></p>';
        echo isset($jaminan['jaminan_penawaran']) ? '<p>'.$jaminan['jaminan_penawaran'].'</p>' : '<p>-</p>';
    }
    if($jaminan_pelaksanaan)  {
        echo '<p><strong>Jaminan Pelaksanaan</strong></p>';
        echo isset($jaminan['jaminan_pelaksanaan']) ? '<p>'.$jaminan['jaminan_pelaksanaan'].'</p>' : '<p>-</p>';
    }
    if($jaminan_pemeliharaan)  {
        echo '<p><strong>Jaminan Pemeliharaan</strong></p>';
        echo isset($jaminan['jaminan_pemeliharaan']) ? '<p>'.$jaminan['jaminan_pemeliharaan'].'</p>' : '<p>-</p>';
    }
}