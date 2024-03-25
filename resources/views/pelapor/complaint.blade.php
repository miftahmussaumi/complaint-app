@extends('template')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex bd-highlight mb-3">
                        <div class="mr-auto p-2 bd-highlight">
                            <h3>Data Permintaan Layanan</h3>
                        </div>
                        <div class="p-2 bd-highlight">
                            <button onclick="window.location='/form-comp'" type=" button" class="btn mb-1 btn-primary">Permintaan <span class="btn-icon-right"><i class="fa fa-plus"></i></span>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table style="color: #2D3134;" class="table table-striped table-bordered zero-configuration">
                            <thead>
                                <tr>
                                    <th>Tanggal Permintaan</th>
                                    <th>No Inventaris</th>
                                    <th>Kategori Layanan</th>
                                    <th>Waktu Max Pengerjaan</th>
                                    <th>Status</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dtLap as $data)
                                <tr>
                                    <td>{{ $data->tgl_masuk }}</td>
                                    <td>{{ $data->no_inv_aset }}</td>
                                    <td>{{ $data->kat_layanan }}</td>
                                    @if($data->status_terbaru == 'reqAddTime')
                                    <td style="color: red;">{{ $data->tgl_akhir_pengerjaan }}</td>
                                    @elseif($data->waktu_tambahan != null)
                                    <?php
                                    $tanggalDeadline = $data->deadline;
                                    $waktu_tambahan = $data->waktu_tambahan;
                                    // Mengubah format tanggal ke format Y-m-d strtotime()
                                    $tanggalBaruTimestamp = strtotime(date('Y-m-d H:i', strtotime(str_replace('-', '/', $tanggalDeadline))) . " +$waktu_tambahan days");
                                    // Menguubah tanggal baru kembali ke format d-m-Y
                                    $tanggalBaru = date('d-m-Y', $tanggalBaruTimestamp) . ' (' . date('H:i', $tanggalBaruTimestamp) . ' WIB)';
                                    ?>
                                    <td>{{ $tanggalBaru }} </td>
                                    @else
                                    <td>{{ $data->tgl_akhir_pengerjaan }}</td>
                                    @endif
                                    <td>
                                        @if($data->status_terbaru == 'Pengajuan')
                                        <span class="badge badge-dark">Pengajuan</span>
                                        @elseif($data->status_terbaru == 'Diproses')
                                        <span class="badge badge-info">Diproses</span>
                                        @elseif($data->status_terbaru == 'CheckedU')
                                        <span class="badge badge-warning">User Check</span>
                                        @elseif($data->status_terbaru == 'ReqHapus')
                                        <span class="badge badge-warning">Request <i class="fa fa-trash-o" aria-hidden="true"></i></span>
                                        @elseif($data->status_terbaru == 'reqAddTime')
                                        <span class="badge badge-warning">Request <i class="fa fa-clock-o" aria-hidden="true"></i></span>
                                        @endif
                                    </td>
                                    <td>
                                        <a data-toggle="modal" data-target="#exampleModal{{$data->idlap}}" data-whatever="@getbootstrap"><button class="btn btn-warning btn-sm"><i class="fa fa-eye"></i></button></a>
                                        <a href="{{url('delete-laporan',$data->idlap)}}" onclick="return confirm('Apakah Yakin Hapus Data Ini?')" style="color: #C63F56;"><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button></a>
                                    </td>
                                </tr>
                                @endforeach
                        </table>
                        <!-- ========= MODAL ========= -->
                        @foreach ($dtLap as $datau)
                        <div class="modal fade" id="exampleModal{{$datau->idlap}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <form action="{{route('laporan-update',$datau->idlap)}}" method="post">
                                    {{csrf_field()}}
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            @if($datau->status_terbaru == 'reqAddTime')
                                            <h5 class="modal-title" id="exampleModalLabel"> Pengajuan Penambahan Waktu</h5>
                                            @else
                                            <h5 class="modal-title" id="exampleModalLabel">Detail Permintaan Layanan</h5>
                                            @endif
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <table style="color: #2D3134;">
                                                <tr>
                                                    <td style="width: 150px; height: 25px;">No Inventaris Aset</td>
                                                    <td style="width: 15px;">:</td>
                                                    <td>{{$datau->no_inv_aset}}</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 150px; height: 25px;">Kategori Layanan</td>
                                                    <td style="width: 15px;">:</td>
                                                    <td>{{$datau->kat_layanan}}</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 150px; height: 25px;">Jenis Layanan</td>
                                                    <td style="width: 15px;">:</td>
                                                    <td>{{$datau->jenis_layanan}}</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 150px; height: 25px;">Detail</td>
                                                    <td style="width: 15px;">:</td>
                                                    <td>{{$datau->det_layanan}}</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 150px; height: 25px;">Waktu Pengerjaan</td>
                                                    <td style="width: 15px;">:</td>
                                                    <td>{{ $datau->tgl_awal_pengerjaan }} <i><b>sampai</b></i></td>
                                                </tr>
                                                @if($datau->waktu_tambahan != null)
                                                <?php
                                                $tanggalDeadline = $datau->deadline;
                                                $waktu_tambahan = $datau->waktu_tambahan;
                                                // Mengubah format tanggal ke format Y-m-d strtotime()
                                                $tanggalBaruTimestamp = strtotime(date('Y-m-d H:i', strtotime(str_replace('-', '/', $tanggalDeadline))) . " +$waktu_tambahan days");
                                                // Menguubah tanggal baru kembali ke format d-m-Y
                                                $tanggalBaru = date('d-m-Y', $tanggalBaruTimestamp) . ' (' . date('H:i', $tanggalBaruTimestamp) . ' WIB)';
                                                ?>
                                                <tr>
                                                    <td style="width: 150px; height: 25px;"></td>
                                                    <td style="width: 15px;"></td>
                                                    <td style="color: #235EC4;">{{ $tanggalBaru }} </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 150px; height: 25px;">Waktu Tambahan</td>
                                                    <td style="width: 15px;">:</td>
                                                    <td>{{ $datau->waktu_tambahan }} </td>
                                                </tr>
                                                @else
                                                <tr>
                                                    <td style="width: 150px; height: 25px;"></td>
                                                    <td style="width: 15px;"></td>
                                                    <td>{{ $datau->tgl_akhir_pengerjaan }} </td>
                                                </tr>
                                                @endif
                                            </table>
                                        </div>
                                        <div class="modal-footer">
                                            @if($datau->status_terbaru == 'reqAddTime')
                                            <button type="submit" class="btn btn-danger" name="action" value="reject">Tolak</button>
                                            <button type="submit" class="btn btn-primary" name="action" value="accept">Terima</button>
                                            @elseif($datau->status_terbaru == 'CheckedU')
                                            <button type="submit" class="btn btn-primary" name="action" value="finished">Permintaan Selesai</button>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endforeach
                        <!-- ========= END MODAL ========= -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection