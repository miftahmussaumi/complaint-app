@extends('template')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex bd-highlight mb-3">
                        <div class="mr-auto p-2 bd-highlight">
                            <h3>Data Laporan</h3>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table style="color: #2D3134;" class="table table-striped table-bordered zero-configuration">
                            <thead>
                                <tr>
                                    <th>Pelapor</th>
                                    <th>No Inventaris</th>
                                    <th>Kategori Layanan</th>
                                    <th>Jenis Layanan</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lap as $dt)
                                <tr>
                                    <td>{{$dt->nama}}</td>
                                    <td>{{$dt->no_inv_aset}}</td>
                                    <td>{{$dt->kat_layanan}}</td>
                                    <td>{{$dt->jenis_layanan}}</td>
                                    <td>
                                        <a href="{{route('cetak-laporan', $dt->idlap)}}"><button class="btn btn-primary btn-sm"><i class="fa fa-file-pdf-o"></i></button></a>
                                        <a data-toggle="modal" data-target="#exampleModal{{$dt->idlap}}" data-whatever="@getbootstrap"><button class="btn btn-warning btn-sm"><i class="fa fa-eye"></i></button></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- ========= MODAL ========= -->
                        @foreach ($lap as $dtp)
                        <div class="modal fade" id="exampleModal{{$dtp->idlap}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <form action="{{route('laporan-update',$dtp->idlap)}}" method="post">
                                    {{csrf_field()}}
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Detail Permintaan Layanan</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <table style="color: #2D3134;">
                                                <tr>
                                                    <td style="width: 150px; height: 25px;">No Inventaris Aset</td>
                                                    <td style="width: 15px;">:</td>
                                                    <td>{{$dtp->no_inv_aset}}</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 150px; height: 25px;">Kategori Layanan</td>
                                                    <td style="width: 15px;">:</td>
                                                    <td>{{$dtp->kat_layanan}}</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 150px; height: 25px;">Jenis Layanan</td>
                                                    <td style="width: 15px;">:</td>
                                                    <td>{{$dtp->jenis_layanan}}</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 150px; height: 25px;">Detail Pelapor</td>
                                                    <td style="width: 15px;">:</td>
                                                    <td>{{$dtp->det_layanan}}</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 150px; height: 25px;">Waktu Pengerjaan</td>
                                                    <td style="width: 15px;">:</td>
                                                    <td>{{ $dtp->tgl_awal_pengerjaan }} <i><b>sampai</b></i></td>
                                                </tr>

                                                <tr>
                                                    <td><b>Keterangan Teknisi IT</b></td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 150px; height: 25px;">Nama Teknisi</td>
                                                    <td style="width: 15px;">:</td>
                                                    <td>{{$dtp->nama_admin}}</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 150px; height: 25px;">Detail Pekerjaan</td>
                                                    <td style="width: 15px;">:</td>
                                                    <td>{{$dtp->det_pekerjaan}}</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 150px; height: 25px;">Keterangan Pekerjaan</td>
                                                    <td style="width: 15px;">:</td>
                                                    @if($dtp->ket_pekerjaan != null)
                                                    <td>{{$dtp->ket_pekerjaan}}</td>
                                                    @else
                                                    <td><i>Tidak ada keterangan</i></td>
                                                    @endif
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="modal-footer">
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