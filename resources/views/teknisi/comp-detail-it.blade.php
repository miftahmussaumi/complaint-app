@extends('template')
@section('style')
<style>
    .garis_verikal {
        border-left: 2px #F54D4D solid;
        height: 20px;
        width: 0px;
        margin-left: 70px;
    }
</style>
@endsection
@section('content')
<div class="row page-titles mx-0">
    <div class="col p-md-0">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:history.back()">Daftar Laporan</a></li>
            <li class="breadcrumb-item active"><a>Detail</a></li>
        </ol>
    </div>
</div>
<!-- row -->

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-6 col-xl-5">
            <div class="card">
                <div class="card-body">
                    <table style="color: #2D3134;">
                        <tr>
                            <td style="width: 150px; height: 25px;">Status</td>
                            <td style="width: 15px;">:</td>
                            <td>
                                @if($laporan->status_terakhir == 'Pengajuan')
                                <span class="badge badge-primary">Open</span>
                                @elseif($laporan->status_terakhir == 'Diproses')
                                <span class="badge badge-info">Process</span>
                                @elseif($laporan->status_terakhir == 'CheckedU')
                                <span class="badge badge-warning">User Check</span>
                                @elseif($laporan->status_terakhir == 'reqAddTime')
                                <span class="badge badge-warning" data-toggle="tooltip" data-placement="right" title="Persetujuan tambah waktu">User Check</span>
                                @elseif($laporan->status_terakhir == 'Selesai')
                                <span class="badge badge-success">Closed</span>
                                @elseif($laporan->status_terakhir == 'Manager')
                                <span class="badge badge-success">Manager</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 150px; height: 25px;">Nama Pelapor</td>
                            <td style="width: 15px;">:</td>
                            <td>{{$laporan->nama_pelapor}}</td>
                        </tr>
                        <tr>
                            <td style="width: 150px; height: 25px;">Jabatan Pelapor</td>
                            <td style="width: 15px;">:</td>
                            <td>{{$laporan->jabatan_pelapor}}</td>
                        </tr>
                        <tr>
                            <td style="width: 150px; height: 25px;">Tanggal Pelaporan</td>
                            <td style="width: 15px;">:</td>
                            <td>{{$laporan->tgl_masuk}}</td>
                        </tr>
                        @if($laporan->tgl_selesai != null)
                        <tr>
                            <td style="width: 150px; height: 25px;">Tanggal Selesai</td>
                            <td style="width: 15px;">:</td>
                            <td>{{$laporan->tgl_selesai}}</td>
                        </tr>
                        @endif
                        <tr>
                            <td style="width: 150px; height: 25px;">No Inventaris Aset</td>
                            <td style="width: 15px;">:</td>
                            <td>{{$laporan->no_inv_aset}}</td>
                        </tr>
                        <tr valign="top">
                            <td style="width: 150px; height: 25px;">Periode Pengerjaan</td>
                            <td style="width: 15px;">:</td>
                            <td>{{$laporan->tgl_awal_pengerjaan}}
                                <div class="garis_verikal"></div>
                                @if($laporan->waktu_tambahan != null)
                                <?php
                                $tanggalDeadline        = $laporan->deadline;
                                $waktu_tambahan         = $laporan->waktu_tambahan;
                                $tanggalBaruTimestamp   = strtotime(date('Y-m-d H:i', strtotime(str_replace('-', '/', $tanggalDeadline))) . " +$waktu_tambahan days");
                                $tanggalBaru_format     = date('d M Y', $tanggalBaruTimestamp) . ', ' . date('H:i', $tanggalBaruTimestamp) . ' WIB';
                                $tanggalBaru            = date('d-m-Y', $tanggalBaruTimestamp);
                                ?>
                                <!-- MENAMPILKAN TANGGAL BARU SETELAH PENAMBAHAN WAKTU -->
                                <span style="color: #3167D5;">{{ $tanggalBaru_format }}</span>
                                @else
                                {{ $laporan->tgl_akhir_pengerjaan }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 150px; height: 25px;">Waktu Tambahan</td>
                            <td style="width: 15px;">:</td>
                            @if($laporan->waktu_tambahan != null)
                            <td>{{$laporan->waktu_tambahan}} hari</td>
                            @else
                            <td>-</td>
                            @endif
                        </tr>
                    </table> <br>
                    <table>
                        <tr>
                            @if($laporan->status_terakhir != 'Selesai' && $laporan->status_terakhir != 'Manager')
                            @if($laporan->status_terakhir == 'Pengajuan')
                            <td>
                                <form action="{{route('proses-laporan',$laporan->id)}}" method="post">
                                    {{csrf_field()}}
                                    <button class="btn btn-primary" type="submit" name="action" value="process">Proses</button>
                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModal{{$laporan->id}}" data-whatever="@getbootstrap">Tolak</button>
                                </form>
                            </td>
                            @endif

                            @if($laporan->waktu_tambahan_peng == null && $laporan->status_terakhir != 'Pengajuan' && $laporan->status_terakhir != 'CheckedU' && $laporan->status_terakhir != 'reqAddTime')
                            <td style="width: 100px;">
                                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#exampleModal1{{$laporan->id}}" data-whatever="@getbootstrap"><i class="fa fa-plus" aria-hidden="true"></i> Waktu</button>
                            </td>
                            <td>
                                <form action="{{route('laporan-selesai-it',$laporan->id)}}" method="POST">
                                    {{csrf_field()}}
                                    @if($count > 0)
                                    <button class="btn btn-success" disabled type="submit" data-toggle="tooltip" data-placement="bottom" title="Isikan Detail dan Keterangan Pekerjaan dahulu"><i class="fa fa-check" aria-hidden="true"></i> Laporan</button>
                                    @else
                                    <button class="btn btn-success" type="submit" data-toggle="tooltip" data-placement="bottom" title="Pekerjaan Laporan Selesai"><i class="fa fa-check" aria-hidden="true"></i> Laporan</button>
                                    @endif
                                </form>
                            </td>
                            @endif
                            @endif
                        </tr>
                    </table>
                    <!-- ========= MODAL ALASAN PENOLAKAN ========= -->
                    <div class="modal fade" id="exampleModal{{$laporan->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form action="{{route('proses-laporan',$laporan->id)}}" method="post">
                                {{csrf_field()}}
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="message-text" class="col-form-label">Alasan Penolakan:</label>
                                            <textarea name="keterangan" style="height: 100px;" class="form-control" placeholder="Masukkan Keterangan"></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" name="action" value="reject" class="btn btn-primary">Kirim</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- ========= END MODAL ALASAN PENOLAKAN ========= -->
                </div>
            </div>
        </div>
        <!-- ========= MODAL PENAMBAHAN WAKTU ========= -->
        <div class="modal fade" id="exampleModal1{{$laporan->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{route('tambah-waktu',$laporan->id)}}" method="post">
                    {{csrf_field()}}
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Tambah Waktu Pengerjaan</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Penambahan waktu:</label>
                                <div class="input-group mb-3">
                                    <input name="waktu_tambahan" style="width: 70px;" type="number" class="form-control" min="1">
                                    <div class="input-group-prepend"><span class="input-group-text">hari</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="message-text" class="col-form-label">Keterangan:</label>
                                <textarea name="keterangan" style="height: 100px;" class="form-control" placeholder="Masukkan Keterangan"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Kirim</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- ========= END MODAL PENAMBAHAN WAKTU ========= -->

        <!-- ========= CARD DETAIL LAPORAN ========= -->
        <div class="col-lg-6 col-xl-7">
            <?php $no = 0; ?>
            @foreach($detlaporan as $dtl)
            <?php $no++ ?>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="mr-auto p-2 bd-highlight">
                            <b>Laporan {{$no}}</b>
                        </div>
                        @if($dtl->status_terakhir != 'Selesai' && $dtl->status_terakhir != 'Manager')
                        <div class="p-2 bd-highlight">
                            <i style="color: #3167D5" type="button" data-toggle="modal" data-target="#exampleModalP{{$dtl->id_det}}" data-whatever="@getbootstrap" class="fa fa-pencil-square-o"></i>
                        </div>
                        @endif
                    </div>
                    <table style="color: #2D3134;">
                        <tr>
                            <td style="width: 150px; height: 25px;">Kategori Laporan</td>
                            <td style="width: 15px;">:</td>
                            <td>{{$dtl->kat_layanan}}</td>
                        </tr>
                        <tr>
                            <td style="width: 150px; height: 25px;">Jenis Laporan</td>
                            <td style="width: 15px;">:</td>
                            <td>{{$dtl->jenis_layanan}}</td>
                        </tr>
                        <tr valign="top">
                            <td style="width: 150px; height: 30px;">Detail Laporan</td>
                            <td style="width: 15px;">:</td>
                            <td>{{$dtl->det_layanan}}</td>
                        </tr>
                        @if($dtl->det_pekerjaan != null && $dtl->ket_pekerjaan != null)
                        <tr>
                            <td style="width: 150px; height: 25px;"><b>Teknisi IT</b></td>
                            <td style="width: 15px;"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="width: 150px; height: 25px;">Detail Pekerjaan</td>
                            <td style="width: 15px;">:</td>
                            <td>{{$dtl->det_pekerjaan}}</td>
                        </tr>
                        <tr>
                            <td style="width: 150px; height: 25px;">Keterangan Pekerjaan</td>
                            <td style="width: 15px;">:</td>
                            <td>{{$dtl->ket_pekerjaan}}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
            @endforeach
        </div>
        <!-- ========= CARD DETAIL LAPORAN ========= -->

        <!-- ========= MODAL DETAIL DAN KETERANGAN PEKERJAAN ========= -->
        @foreach($detlaporan as $dtl2)
        <div class="modal fade" id="exampleModalP{{$dtl2->id_det}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{route('detail-pekerjaan-it',$dtl2->id_det)}}" method="post">
                    {{csrf_field()}}
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Form Input Pengerjaan Laporan</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <table style="color: #2D3134;">
                                <tr>
                                    <td style="width: 150px; height: 25px;">Kategori Laporan</td>
                                    <td style="width: 15px;">:</td>
                                    <td>{{$dtl2->kat_layanan}}</td>
                                </tr>
                                <tr>
                                    <td style="width: 150px; height: 25px;">Jenis Laporan</td>
                                    <td style="width: 15px;">:</td>
                                    <td>{{$dtl2->jenis_layanan}}</td>
                                </tr>
                                <tr>
                                    <td style="width: 150px; height: 25px;">Laporan Permasalahan</td>
                                    <td style="width: 15px;">:</td>
                                    <td>{{$dtl2->det_layanan}}</td>
                                </tr>
                            </table>
                            <form>
                                <div class="form-group">
                                    <label for="recipient-name" class="col-form-label">Detail Pekerjaan:</label>
                                    <textarea name="det_pekerjaan" class="form-control" id="message-text"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="message-text" class="col-form-label">Keterangan Pekerjaan:</label>
                                    <textarea name="ket_pekerjaan" class="form-control" id="message-text"></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endforeach
        <!-- ========= END MODAL DETAIL DAN KETERANGAN PEKERJAAN ========= -->
    </div>
</div>
@endsection