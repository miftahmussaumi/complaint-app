@if($data->waktu_tambahan != null && $data->status_terbaru != 'reqAddTime')
<!-- CODINGAN PENAMBAHAN TANGGAL DEADLINE -->
<?php
$tanggalDeadline = $data->deadline;
$waktu_tambahan = $data->waktu_tambahan;
$tanggalBaruTimestamp = strtotime(date('Y-m-d H:i', strtotime(str_replace('-', '/', $tanggalDeadline))) . " +$waktu_tambahan days");
$tanggalBaru = date('d-m-Y', $tanggalBaruTimestamp);
$tanggalBaruF = date('d-m-Y', $tanggalBaruTimestamp) . ' (' . date('H:i', $tanggalBaruTimestamp) . ' WIB)';
?>
<!-- END CODINGAN PENAMBAHAN TANGGAL DEADLINE -->

<!-- CODINGAN H-1 DEADLINE SESUAI DENGAN TANGGAL BARU -->
<?php
$tanggalDeadline = $tanggalBaru;
$tanggalObjek = new DateTime($tanggalDeadline);
$tanggalHariIni = new DateTime();
$tanggalHariIni->modify('+1 day');
?>
<span style="color: #3167D5;">{{ $tanggalBaruF }}</span>
@if($data->status_terbaru != 'CheckedU')
@if ($tanggalObjek->format('Y-m-d') > $tanggalHariIni->format('Y-m-d'))
<a data-toggle="modal" style="color: #3167D5;" data-target="#exampleModalWaktu{{$data->idlap}}" data-whatever="@getbootstrap"><i class="fa fa-plus"></i></a>
@elseif ($tanggalObjek->format('Y-m-d') == $tanggalHariIni->format('Y-m-d'))
<a data-toggle="modal" style="color: #3167D5;" data-target="#exampleModalWaktu{{$data->idlap}}" data-whatever="@getbootstrap"><i class="fa fa-plus"></i></a>
<a data-toggle="modal" data-target="#exampleModalWaktu{{$data->idlap}}" data-whatever="@getbootstrap"></i></a>
<br>
<i style="color: #DF1839; font-size: 12px;">Deadline 1 hari lagi </i>
@endif
@endif
<!-- END CODINGAN H-1 DEADLINE SESUAI DENGAN TANGGAL BARU -->
@else
{{ $data->tgl_akhir_pengerjaan }}
<!-- CODINGAN H-1 DEADLINE SESUAI DENGAN TANGGAL BARU -->
<?php
$tanggalDeadline = $data->deadline;
$tanggalObjek = new DateTime($tanggalDeadline);
$tanggalHariIni = new DateTime(); //Tanggal hari ini
$tanggalHariIni->modify('+1 day');
?>
@if ($tanggalObjek->format('Y-m-d') > $tanggalHariIni->format('Y-m-d'))
<a data-toggle="modal" style="color: #3167D5;" data-target="#exampleModalWaktu{{$data->idlap}}" data-whatever="@getbootstrap"><i class="fa fa-plus"></i></a>
@elseif ($tanggalObjek->format('Y-m-d') == $tanggalHariIni->format('Y-m-d'))
<a data-toggle="modal" style="color: #3167D5;" data-target="#exampleModalWaktu{{$data->idlap}}" data-whatever="@getbootstrap"><i class="fa fa-plus"></i></a>
<a data-toggle="modal" data-target="#exampleModalWaktu{{$data->idlap}}" data-whatever="@getbootstrap"></i></a>
<br>
<i style="color: #DF1839; font-size: 12px;">Deadline 1 hari lagi </i>
@endif
<!-- END CODINGAN H-1 DEADLINE SESUAI DENGAN TANGGAL BARU -->
@endif