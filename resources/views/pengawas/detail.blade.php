@extends('template')
@section('content')
<div class="row page-titles mx-0">
    <div class="col p-md-0">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/list-laporan">Laporan</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">detail</a></li>
        </ol>
    </div>
</div>
<!-- row -->

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-between">
                        <div class="col-5">
                            <b>Data Cetak Laporan</b><br><br>
                            <div class="basic-form">
                                <form action="{{route('laporan-akhir',$dtp->idlap)}}" method="post">
                                    {{csrf_field()}}
                                    <table style="color: #2D3134;">
                                        <tr>
                                            <td style="width: 100px;">Nomor</td>
                                            <td style="width: 10px;">:</td>
                                            <td style="height: 50px;">
                                                <input type="text" name="nomor" class="form-control form-control-sm" placeholder="Nomor Laporan" value="{{$dtp->nomor}}">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 100px;">Tanggal</td>
                                            <td style="width: 10px;">:</td>
                                            <td style="height: 50px;">
                                                <input type="date" name="tanggal" class="form-control form-control-sm" placeholder="Tanggal Laporan" value="{{$dtp->tanggal}}">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 100px;">Versi</td>
                                            <td style="width: 10px;">:</td>
                                            <td style="height: 50px;">
                                                <input type="text" name="versi" class="form-control form-control-sm" placeholder="Versi Laporan" value="{{$dtp->versi}}">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 100px;">Halaman</td>
                                            <td style="width: 10px;">:</td>
                                            <td style="height: 50px;">
                                                <input type="text" name="halaman" class="form-control form-control-sm" placeholder="Halaman Laporan" value="{{$dtp->halaman}}">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 100px;">No Ref</td>
                                            <td style="width: 10px;">:</td>
                                            <td style="height: 50px;">
                                                <input type="text" name="no_ref" class="form-control form-control-sm" placeholder="No Referensi Laporan" value="{{$dtp->no_ref}}">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 100px;">Business Area</td>
                                            <td style="width: 10px;">:</td>
                                            <td style="height: 50px;">
                                                <input type="text" name="bisnis_area" class="form-control form-control-sm" placeholder="Business Area" value="{{$dtp->bisnis_area}}">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 100px;"></td>
                                            <td style="width: 10px;"></td>
                                            <td style="height: 50px;">
                                                @if($dtp->nomor != null or $dtp->tanggal != null or $dtp->no_ref != null or $dtp->bisnis_area != null or $dtp->versi != null or $dtp->halaman != null)
                                                <button type="submit" class="btn btn-primary">Update</button>
                                                @else
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </form>
                            </div>
                        </div>
                        <div class="col-7">
                            <b>Data Laporan</b><br><br>
                            <table style="color: #2D3134;">
                                <tr>
                                    <td><b>Data Pelapor</b></td>
                                </tr>
                                <tr>
                                    <td style="width: 150px; height: 25px;">Nama</td>
                                    <td style="width: 15px;">:</td>
                                    <td>{{$dtp->nama}}</td>
                                </tr>
                                <tr valign="top">
                                    <td style="width: 150px; height: 30px;">NIPP</td>
                                    <td style="width: 15px;">:</td>
                                    <td>{{$dtp->nipp}}</td>
                                </tr>
                                <tr>
                                    <td><b>Isi Laporan</b></td>
                                </tr>
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
                                <tr valign="top">
                                    <td style="width: 150px; height: 30px;">Waktu Pengerjaan</td>
                                    <td style="width: 15px;">:</td>
                                    <td>
                                        {{ $dtp->tgl_awal_pengerjaan }} <i><b>sampai</b></i> {{ $dtp->tgl_akhir_pengerjaan }}
                                    </td>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection