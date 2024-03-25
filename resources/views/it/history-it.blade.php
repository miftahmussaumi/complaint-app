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
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- <h4 class="d-inline">Cards Types</h4>
            <p>The building block of a card is the <code class="highlighter-rouge">.card-body</code>. Use it whenever you need a padded section within a card.</p> -->
            @foreach($data as $dt)
            <div class="card text-left" style="color: #4F4B4B;">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            Tanggal <br><br>
                            <b>Tanggal Kirim</b><br>
                            {{$dt->tgl_masuk}}<br><br>
                            <b>Tanggal Selesai</b><br>
                            {{$dt->tgl_masuk}}
                        </div>
                        <div class="col">
                            No Inventaris <br><br>
                            <b>{{$dt->no_inv_aset}}</b>
                        </div>
                        <div class="col">
                            Kategori - Jenis <br><br>
                            <b>{{$dt->kat_layanan}} - {{$dt->jenis_layanan}}</b>
                        </div>
                        <div class="col">
                            Pengerjaan <br><br>
                            <b>{{$dt->tgl_awal_pengerjaan}}</b><br>
                            <div class="garis_verikal"></div>
                            <b>{{$dt->tgl_akhir_pengerjaan}}</b>
                        </div>
                        <div class="col">
                            Waktu Tambahan <br><br>
                            @if($dt->waktu_tambahan == null OR $dt->waktu_tambahan == 0)
                            <i>tidak ada tambahan waktu</i>
                            @else
                            <b>{{$dt->waktu_tambahan}}</b>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="button" class="btn mb-1 btn-outline-primary" data-toggle="modal" data-target="#basicModal{{$dt->id}}">Detail</button>
                </div>
                <div class="modal fade" id="basicModal{{$dt->id}}">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Detail Permintaan Layanan</h5>
                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                @foreach($dt->history as $dthist)
                                <table>
                                    <tr>
                                        <td style="width: 80px;" rowspan="2" align=left valign=top>
                                            @if($dthist->status_laporan == 'Pengajuan')
                                            <span class="badge badge-dark">Pengajuan</span>
                                            @elseif($dthist->status_laporan == 'Diproses')
                                            <span class="badge badge-info">Diproses</span>
                                            @elseif($dthist->status_laporan == 'Selesai')
                                            <span class="badge badge-success">Selesai</span>
                                            @elseif($dthist->status_laporan == 'Dibatalkan')
                                            <span class="badge badge-danger">Dibatalkan</span>
                                            @elseif($dthist->status_laporan == 'ReqHapus')
                                            <span class="badge badge-warning">Request <i class="fa fa-trash-o" aria-hidden="true"></i></span>
                                            @elseif($dthist->status_laporan == 'CheckedU')
                                            <span class="badge badge-warning">User Check</span>
                                            @elseif($dthist->status_laporan == 'reqAddTime')
                                            <span class="badge badge-warning">Request <i class="fa fa-clock-o" aria-hidden="true"></i></span>
                                            @endif
                                            <!-- <span class="badge badge-primary">{{$dthist->status_laporan}}</span> -->
                                        </td>
                                        <td style="color: black;">{{$dthist->tanggal}}</td>
                                    </tr>
                                    <tr>
                                        @if($dthist->keterangan != null)
                                        <td style="font-size: 12px; height: 30px;" valign=top>{{$dthist->keterangan}}</td>
                                        @else
                                        <td style="font-size: 12px; height: 30px;" valign=top><i>tidak ada keterangan</i></td>
                                        @endif
                                    </tr>
                                </table>
                                @endforeach
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection