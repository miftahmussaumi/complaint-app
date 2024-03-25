@extends('template')
@section('content')
<div class="row page-titles mx-0">
    <div class="col p-md-0">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/it">List Permintaan Layanan</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)"><i>Proses Layanan</i></a></li>
        </ol>
    </div>
</div>
<!-- row -->

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @foreach($dtLap as $data)
                    <div class="row justify-content-between">
                        <div class="col-lg-5">
                            <table style="color: #2D3134;">
                                <tr>
                                    <td style="width: 150px; height: 25px;">No Inventaris Aset</td>
                                    <td style="width: 15px;">:</td>
                                    <td>{{$data->no_inv_aset}}</td>
                                </tr>
                                <tr>
                                    <td style="width: 150px; height: 25px;">Kategori Layanan</td>
                                    <td style="width: 15px;">:</td>
                                    <td>{{$data->kat_layanan}}</td>
                                </tr>
                                <tr>
                                    <td style="width: 150px; height: 25px;">Jenis Layanan</td>
                                    <td style="width: 15px;">:</td>
                                    <td>{{$data->jenis_layanan}}</td>
                                </tr>
                                <tr>
                                    <td style="width: 150px; height: 25px;">Detail</td>
                                    <td style="width: 15px;">:</td>
                                    <td>{{$data->det_layanan}}</td>
                                </tr>
                                <tr>
                                    <td style="width: 150px; height: 25px;"> Waktu Pengerjaan</td>
                                    <td style="width: 15px;">:</td>
                                    <td>{{ $data->tgl_awal_pengerjaan }} <i><b>sampai</b></i></td>
                                </tr>
                                <tr>
                                    <td style="width: 150px; height: 25px;"></td>
                                    <td style="width: 15px;"></td>
                                    @if($data->waktu_tambahan != null)
                                    <?php
                                    $tanggalDeadline = $data->deadline;
                                    $waktu_tambahan = $data->waktu_tambahan;
                                    // Mengubah format tanggal ke format Y-m-d strtotime()
                                    $tanggalBaruTimestamp = strtotime(date('Y-m-d H:i', strtotime(str_replace('-', '/', $tanggalDeadline))) . " +$waktu_tambahan days");
                                    // Menguubah tanggal baru kembali ke format d-m-Y
                                    $tanggalBaru = date('d-m-Y', $tanggalBaruTimestamp) . ' (' . date('H:i', $tanggalBaruTimestamp) . ' WIB)';
                                    ?>
                                    <td style="color: #235EC4;">{{ $tanggalBaru }} </td>
                                    @else
                                    <td>{{ $data->tgl_akhir_pengerjaan }}</td>
                                    @endif
                                </tr>
                            </table>
                        </div>
                        <div class="col-lg-7">
                            <form action="{{route('pelayanan-selesai',$data->idlap)}}" method="post">
                                {{csrf_field()}}
                                <div class="form-group">
                                    <label>Detail Kegiatan <span style="color: red;">*</span></label>
                                    <textarea name="det_pekerjaan" style="height: 100px;" type="text" class="form-control" placeholder="Isikan detail kegiatan"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Keterangan</label>
                                    <textarea name="ket_pekerjaan" style="height: 100px;" type="text" class="form-control" placeholder="Isikan keterangan (opsional)"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Pelayanan Selesai</button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection