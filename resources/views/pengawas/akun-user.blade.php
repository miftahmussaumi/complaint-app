@extends('template')
@section('content')
<!-- {{Auth::guard('pengawas')->user()->id}} -->
<div class="container-fluid">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Data Pengguna</h4>
                <ul class="nav nav-pills mb-3">
                    <li class="nav-item"><a href="#navpills-1" class="nav-link active" data-toggle="tab" aria-expanded="false">Pelapor</a>
                    </li>
                    <li class="nav-item"><a href="#navpills-2" class="nav-link" data-toggle="tab" aria-expanded="false">Teknisis IT</a>
                    </li>
                </ul>
                <div class="tab-content br-n pn">
                    <div id="navpills-1" class="tab-pane active">
                        <div class="row align-items-center">
                            <div class="col-sm-12">
                                <table style="color: #2D3134;" class="table table-hover">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>NIPP</th>
                                        <th>Jabatan</th>
                                        <th>Divisi</th>
                                        <th>Email</th>
                                        <th>Telepon</th>
                                        <th>Total Pengaduan</th>
                                    </tr>
                                    @foreach($pelapor as $dtp)
                                    <tr>
                                        <?php $no = 1; ?>
                                        <td>{{$no++}}</td>
                                        <td>{{$dtp->nama}}</td>
                                        <td>{{$dtp->nipp}}</td>
                                        <td>{{$dtp->jabatan}}</td>
                                        <td>{{$dtp->divisi}}</td>
                                        <td>{{$dtp->email}}</td>
                                        <td>{{$dtp->telepon}}</td>
                                        <td>{{$dtp->jumlah_laporan}}</td>
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                    <div id="navpills-2" class="tab-pane">
                        <div class="row align-items-center">
                            <div class="col-sm-12">
                                <table style="color: #2D3134;" class="table table-hover">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>NIPP</th>
                                        <th>Jabatan</th>
                                        <th>Email</th>
                                        <th>Total Penyelesaian</th>
                                    </tr>
                                    @foreach($it as $dtit)
                                    <tr>
                                        <?php $no = 1; ?>
                                        <td>{{$no++}}</td>
                                        <td>{{$dtit->nama}}</td>
                                        <td>{{$dtit->nipp}}</td>
                                        <td>{{$dtit->jabatan}}</td>
                                        <td>{{$dtit->email}}</td>
                                        <td>{{$dtit->jumlah_laporan}}</td>
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection