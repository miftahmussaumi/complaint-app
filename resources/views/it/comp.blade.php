@extends('template')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex bd-highlight mb-3">
                        <div class="mr-auto p-2 bd-highlight">
                            <h4 class="card-title">Data Permintaan Layanan</h4>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table style="color: #2D3134;" class="table table-striped table-bordered zero-configuration">
                            <thead>
                                <tr>
                                    <th>Tanggal Permintaan</th>
                                    <th>Pelapor</th>
                                    <th>No Inventaris</th>
                                    <th>Kategori Layanan</th>
                                    <th>Jenis Layanan</th>
                                    <th>Waktu Max Pengerjaan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dtLap as $data)
                                <tr>
                                    <td>{{ $data->tgl_masuk }}</td>
                                    <td>{{ $data->nama }}</td>
                                    <td>{{ $data->no_inv_aset }}</td>
                                    <td>{{ $data->kat_layanan }}</td>
                                    <td>{{ $data->jenis_layanan }}</td>
                                    <td>
                                        @if($data->waktu_tambahan != null && $data->status_terbaru == 'reqAddTime')
                                        {{$data->tgl_akhir_pengerjaan}}

                                        @elseif($data->waktu_tambahan != null && $data->status_terbaru == 'Diproses')
                                        <!-- CODINGAN PENAMBAHAN TANGGAL DEADLINE -->
                                        <?php
                                        $tanggalDeadline = $data->deadline;
                                        $waktu_tambahan = $data->waktu_tambahan;
                                        $tanggalBaruTimestamp = strtotime(date('Y-m-d H:i', strtotime(str_replace('-', '/', $tanggalDeadline))) . " +$waktu_tambahan days");
                                        $tanggalBaru = date('d-m-Y', $tanggalBaruTimestamp);
                                        $tanggalBaruF = date('d-m-Y', $tanggalBaruTimestamp) . ' (' . date('H:i', $tanggalBaruTimestamp) . ' WIB)';
                                        ?>
                                        <!-- END CODINGAN PENAMBAHAN TANGGAL DEADLINE -->

                                        <!-- CODINGAN MERUBAH WARNA TANGGAL SETELAH PENAMBAHAN WAKTU-->
                                        <?php
                                        $tanggalDeadline = $tanggalBaru;
                                        $newDeadline = new DateTime($tanggalDeadline);
                                        $today = new DateTime();
                                        $tanggalHariIni = new DateTime();
                                        $tanggalHariIni->modify('+1 day');
                                        ?>
                                        <!-- END CODINGAN MERUBAH WARNA TANGGAL SETELAH PENAMBAHAN WAKTU-->

                                        <!-- CODINGAN PENAMBAHAN BUTTON +WAKTU-->
                                        @if ($newDeadline->format('Y-m-d') > $tanggalHariIni->format('Y-m-d'))
                                        <span style="color: #3167D5;">{{ $tanggalBaruF }}</span>
                                        <a data-toggle="modal" style="color: #3167D5;" data-target="#exampleModalWaktu{{$data->idlap}}" data-whatever="@getbootstrap"><i class="fa fa-plus"></i></a>
                                        @elseif ($newDeadline->format('Y-m-d') == $tanggalHariIni->format('Y-m-d'))
                                        <span style="color: #3167D5;">{{ $tanggalBaruF }}</span>
                                        <a data-toggle="modal" style="color: #3167D5;" data-target="#exampleModalWaktu{{$data->idlap}}" data-whatever="@getbootstrap"><i class="fa fa-plus"></i></a>
                                        <br>
                                        <i style="color: #DF1839; font-size: 12px;">Deadline 1 hari lagi </i>
                                        @else
                                        <span style="color: #3167D5;">{{ $tanggalBaruF }}</span>
                                        <i style="color: #DF1839; font-size: 12px;">Deadline sudah lewat </i>
                                        @endif
                                        <!-- END CODINGAN PENAMBAHAN BUTTON +WAKTU-->

                                        @elseif($data->waktu_tambahan != null && $data->status_terbaru == 'CheckedU')
                                        <!-- CODINGAN PENAMBAHAN TANGGAL DEADLINE -->
                                        <?php
                                        $tanggalDeadline = $data->deadline;
                                        $waktu_tambahan = $data->waktu_tambahan;
                                        $tanggalBaruTimestamp = strtotime(date('Y-m-d H:i', strtotime(str_replace('-', '/', $tanggalDeadline))) . " +$waktu_tambahan days");
                                        $tanggalBaru = date('d-m-Y', $tanggalBaruTimestamp);
                                        $tanggalBaruF = date('d-m-Y', $tanggalBaruTimestamp) . ' (' . date('H:i', $tanggalBaruTimestamp) . ' WIB)';
                                        ?>
                                        <!-- END CODINGAN PENAMBAHAN TANGGAL DEADLINE -->

                                        <!-- CODINGAN MERUBAH WARNA TANGGAL SETELAH PENAMBAHAN WAKTU-->
                                        <?php
                                        $tanggalDeadline = $tanggalBaru;
                                        $newDeadline = new DateTime($tanggalDeadline);
                                        $today = new DateTime();
                                        $tanggalHariIni = new DateTime();
                                        $tanggalHariIni->modify('+1 day');
                                        ?>
                                        <!-- END CODINGAN MERUBAH WARNA TANGGAL SETELAH PENAMBAHAN WAKTU-->
                                        <span style="color: #3167D5;">{{ $tanggalBaruF }}</span>


                                        @elseif($data->waktu_tambahan == null && $data->status_terbaru != 'CheckedU')
                                        <!-- CODINGAN PENAMBAHAN TANGGAL DEADLINE -->
                                        <?php
                                        $tanggalDeadline = $data->deadline;
                                        $waktu_tambahan = $data->waktu_tambahan;
                                        $tanggalBaruTimestamp = strtotime(date('Y-m-d H:i', strtotime(str_replace('-', '/', $tanggalDeadline))) . " +$waktu_tambahan days");
                                        $tanggalBaru = date('d-m-Y', $tanggalBaruTimestamp);
                                        $tanggalBaruF = date('d-m-Y', $tanggalBaruTimestamp) . ' (' . date('H:i', $tanggalBaruTimestamp) . ' WIB)';
                                        ?>
                                        <!-- END CODINGAN PENAMBAHAN TANGGAL DEADLINE -->

                                        <!-- CODINGAN MERUBAH WARNA TANGGAL SETELAH PENAMBAHAN WAKTU-->
                                        <?php
                                        $tanggalDeadline = $tanggalBaru;
                                        $newDeadline = new DateTime($tanggalDeadline);
                                        $today = new DateTime();
                                        $tanggalHariIni = new DateTime();
                                        $tanggalHariIni->modify('+1 day');
                                        ?>
                                        <!-- END CODINGAN MERUBAH WARNA TANGGAL SETELAH PENAMBAHAN WAKTU-->

                                        <!-- CODINGAN PENAMBAHAN BUTTON +WAKTU-->
                                        @if ($newDeadline->format('Y-m-d') > $tanggalHariIni->format('Y-m-d'))
                                        {{$data->tgl_akhir_pengerjaan}}
                                        <a data-toggle="modal" style="color: #3167D5;" data-target="#exampleModalWaktu{{$data->idlap}}" data-whatever="@getbootstrap"><i class="fa fa-plus"></i></a>
                                        @elseif ($newDeadline->format('Y-m-d') == $tanggalHariIni->format('Y-m-d'))
                                        {{$data->tgl_akhir_pengerjaan}}
                                        <a data-toggle="modal" style="color: #3167D5;" data-target="#exampleModalWaktu{{$data->idlap}}" data-whatever="@getbootstrap"><i class="fa fa-plus"></i></a>
                                        <br>
                                        <i style="color: #DF1839; font-size: 12px;">Deadline 1 hari lagi </i>
                                        @else
                                        {{$data->tgl_akhir_pengerjaan}}
                                        <i style="color: #DF1839; font-size: 12px;">Deadline sudah lewat </i>
                                        @endif
                                        <!-- END CODINGAN PENAMBAHAN BUTTON +WAKTU-->

                                        @elseif($data->waktu_tambahan == null && $data->status_terbaru != 'reqAddTime')
                                        <!-- CODINGAN PENAMBAHAN TANGGAL DEADLINE -->
                                        <?php
                                        $tanggalDeadline = $data->deadline;
                                        $waktu_tambahan = $data->waktu_tambahan;
                                        $tanggalBaruTimestamp = strtotime(date('Y-m-d H:i', strtotime(str_replace('-', '/', $tanggalDeadline))) . " +$waktu_tambahan days");
                                        $tanggalBaru = date('d-m-Y', $tanggalBaruTimestamp);
                                        $tanggalBaruF = date('d-m-Y', $tanggalBaruTimestamp) . ' (' . date('H:i', $tanggalBaruTimestamp) . ' WIB)';
                                        ?>
                                        <!-- END CODINGAN PENAMBAHAN TANGGAL DEADLINE -->

                                        <!-- CODINGAN MERUBAH WARNA TANGGAL SETELAH PENAMBAHAN WAKTU-->
                                        <?php
                                        $tanggalDeadline = $tanggalBaru;
                                        $newDeadline = new DateTime($tanggalDeadline);
                                        $today = new DateTime();
                                        $tanggalHariIni = new DateTime();
                                        $tanggalHariIni->modify('+1 day');
                                        ?>
                                        <!-- END CODINGAN MERUBAH WARNA TANGGAL SETELAH PENAMBAHAN WAKTU-->

                                        <!-- CODINGAN PENAMBAHAN BUTTON +WAKTU-->
                                        @if ($newDeadline->format('Y-m-d') > $tanggalHariIni->format('Y-m-d'))
                                        {{$data->tgl_akhir_pengerjaan}}
                                        <a data-toggle="modal" style="color: #3167D5;" data-target="#exampleModalWaktu{{$data->idlap}}" data-whatever="@getbootstrap"><i class="fa fa-plus"></i></a>
                                        @elseif ($newDeadline->format('Y-m-d') == $tanggalHariIni->format('Y-m-d'))
                                        {{$data->tgl_akhir_pengerjaan}}
                                        <a data-toggle="modal" style="color: #3167D5;" data-target="#exampleModalWaktu{{$data->idlap}}" data-whatever="@getbootstrap"><i class="fa fa-plus"></i></a>
                                        <br>
                                        <i style="color: #DF1839; font-size: 12px;">Deadline 1 hari lagi </i>
                                        @else
                                        {{$data->tgl_akhir_pengerjaan}}
                                        <i style="color: #DF1839; font-size: 12px;">Deadline sudah lewat </i>
                                        @endif
                                        <!-- END CODINGAN PENAMBAHAN BUTTON +WAKTU-->


                                        @endif
                                    </td>
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
                                        @if($data->status_terbaru == 'Pengajuan' or $data->status_terbaru == 'ReqHapus')
                                        <a data-toggle="modal" data-target="#exampleModalDetail{{$data->idlap}}" data-whatever="@getbootstrap"><button class="btn btn-dark btn-sm"><i class="fa fa-bars"></i></button></a>
                                        @elseif($data->status_terbaru == 'Diproses')
                                        <a href="{{url('comp-detail',$data->idlap)}}"><button class="btn btn-info btn-sm"><i class="fa fa-check "></i></button></a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                        </table>
                        <!-- ========= MODAL PENAMBAHAN WAKTU ========= -->
                        @foreach ($dtLap as $datat)
                        <div class="modal fade" id="exampleModalWaktu{{$datat->idlap}}">
                            <div class="modal-dialog" role="document">
                                <form action="{{route('tambah-waktu',$datat->idlap)}}" method="post">
                                    {{csrf_field()}}
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Tambah Waktu Pengerjaan</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <table>
                                                <tr>
                                                    <td style="width: 150px; height: 25px;">Detail</td>
                                                    <td style="width: 15px;">:</td>
                                                    <td>{{$datat->det_layanan}}</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 150px; height: 25px;"> Awal Pengerjaan</td>
                                                    <td style="width: 15px;">:</td>
                                                    <td>{{ $datat->tgl_awal_pengerjaan }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 150px; height: 25px;"> Awal Pengerjaan</td>
                                                    <td style="width: 15px;">:</td>
                                                    <td>{{ $datat->tgl_akhir_pengerjaan }} </td>
                                                </tr>
                                            </table><br><br>
                                            <div class="basic-form">
                                                <div class="form-row">
                                                    <div class="form-group col-md-4">
                                                        <label>Penambahan waktu</label>
                                                        <div class="input-group mb-3">
                                                            <input name="waktu_tambahan" style="width: 70px;" type="number" class="form-control" min="1">
                                                            <div class="input-group-prepend"><span class="input-group-text">hari</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-8">
                                                        <label>Keterangan <i><small>opsional</small></i></label>
                                                        <textarea name="keterangan" style="height: 100px;" class="form-control" placeholder="Masukkan Keterangan"></textarea>
                                                    </div>
                                                </div>
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
                        @endforeach
                        <!-- ========= END MODAL PENAMBAHAN WAKTU ========= -->
                        <!-- ========= MODAL DETAIL ========= -->
                        @foreach ($dtLap as $datau)
                        <div class="modal fade" id="exampleModalDetail{{$datau->idlap}}">
                            <div class="modal-dialog" role="document">
                                <form action="{{route('proses-laporan',$datau->idlap)}}" method="post">
                                    {{csrf_field()}}
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            @if($datau->status_terbaru == 'ReqHapus')
                                            <h5 class="modal-title" id="exampleModalLabel">Pengajuan Hapus Permintaan Layanan</h5>
                                            @else
                                            <h5 class="modal-title" id="exampleModalLabel">Detail Permintaan Layanan</h5>
                                            @endif
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <table style="color: #2D3134;">
                                                <tr>
                                                    <td><b>Data Pelapor</b></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>Nama</td>
                                                    <td>:</td>
                                                    <td>{{$datau->nama}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Jabatan</td>
                                                    <td>:</td>
                                                    <td>{{$datau->jabatan}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Divisi</td>
                                                    <td>:</td>
                                                    <td>{{$datau->divisi}}</td>
                                                </tr>
                                                <tr style="height: 40px;" valign="top">
                                                    <td>Kontak</td>
                                                    <td>:</td>
                                                    <td>{{$datau->telepon}} / {{$datau->email}}</td>
                                                </tr>
                                                <tr>
                                                    <td><b>Isi Laporan</b></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
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
                                                    <td style="width: 150px; height: 25px;"> Waktu Pengerjaan</td>
                                                    <td style="width: 15px;">:</td>
                                                    <td>{{ $datau->tgl_awal_pengerjaan }} <i><b>sampai</b></i></td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 150px; height: 25px;"></td>
                                                    <td style="width: 15px;"></td>
                                                    <td>{{ $datau->tgl_akhir_pengerjaan }} </td>
                                                </tr>
                                                @if($datau->status_terbaru == 'Diproses')
                                                <div class="basic-form">
                                                    <div class="form-row">
                                                        <div class="form-group">
                                                            <label>Detail Pekerjaan</label>
                                                            <textarea name="" id="" cols="30" rows="10" class="form-control"></textarea>
                                                            <input type="text" class="form-control" placeholder="1234 Main St">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Address 2</label>
                                                            <textarea name="" id="" cols="30" rows="10" class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </table>
                                        </div>
                                        <div class="modal-footer">
                                            @if($datau->status_terbaru == 'Pengajuan')
                                            <button type="submit" name="action" value="process" class="btn btn-info">Proses Layanan</button>
                                            @elseif($datau->status_terbaru == 'ReqHapus')
                                            <button type="submit" name="action" value="delete" class="btn btn-danger">Hapus Permintaan</button>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endforeach
                        <!-- ========= END MODAL DETAIL ========= -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection