@extends('template')

@section('content')
<div class>
    <div class="page-title">
        <div class="title_left">
            <h3>Halaman Data Permintaan Layanan</h3>
        </div>
        <div class="title_right">
            <!-- <button type="button" class="btn btn-round btn-success">Success</button> -->
            <div class="form-group pull-right">
                <a href="/form-comp"><button type="button" class="btn btn-success">Tambah Complaint</button></a>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>Daftar Permintaan Layanan</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">
                            <!-- <p class="text-muted font-13 m-b-30">
                                DataTables has most features enabled by default, so all you need to do to use it with your own tables is to call the construction function: <code>$().DataTable();</code>
                            </p> -->
                            <table id="datatable" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Tanggal Permintaan</th>
                                        <th>No Inventaris</th>
                                        <th>Kategori Layanan</th>
                                        <th>Jenis Layanan</th>
                                        <!-- <th>Detail Layanan</th> -->
                                        <th>Periode Pengerjaan</th>
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
                                        <td>{{ $data->jenis_layanan }} {{$data->idlap}}</td>
                                        <!-- <td>{{ $data->det_layanan }}</td> -->
                                        <td>
                                            @if($data->tgl_awal_pengerjaan == null && $data->tgl_akhir_pengerjaan == null)
                                            <em>menunggu diproses</em>
                                            @else
                                            {{ $data->tgl_awal_pengerjaan }} - {{ $data->tgl_akhir_pengerjaan }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($data->status_terbaru == 'Pengajuan')
                                            <span class="badge badge-dark">Pengajuan</span>
                                            @elseif($data->status_terbaru == 'Diproses')
                                            <span class="badge badge-info">Diproses</span>
                                            @elseif($data->status_terbaru == 'CheckedU')
                                            <span class="badge badge-warning">Pengecekan</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($data->status_terbaru == 'CheckedU')
                                            <a href="" data-toggle="modal" data-target=".bs-example-modal-lg-{{$data->idlap}}"><i class="fa fa-check-square-o"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @foreach($dtLap as $datau)
                        <div class="modal fade bs-example-modal-lg-{{$datau->idlap}}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <form action="{{route('update-laporan-u',$datau->idlap)}}" method="post">
                                    {{csrf_field()}}
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myModalLabel">Detail Permintaan Layanan</h4>
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <h6>Permintaan Layanan :</h6>
                                            <table>
                                                <tr>
                                                    <td style="width: 200px; height: 30px;">No Inventaris Aset</td>
                                                    <td>:</td>
                                                    <td>{{$datau->no_inv_aset}}</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 200px; height: 30px;">Kategori Layanan</td>
                                                    <td>:</td>
                                                    <td>{{$datau->kat_layanan}}</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 200px; height: 30px;">Jenis Layanan</td>
                                                    <td>:</td>
                                                    <td>{{$datau->jenis_layanan}}</td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 200px; height: 30px;">Detail</td>
                                                    <td>:</td>
                                                    <td>{{$datau->det_layanan}}</td>
                                                </tr>
                                                @if($datau->tgl_awal_pengerjaan != null && $datau->tgl_akhir_pengerjaan != null)
                                                <tr>
                                                    <td style="width: 200px; height: 30px;">Waktu Pengerjaan</td>
                                                    <td>:</td>
                                                    <td>
                                                        {{ $datau->tgl_awal_pengerjaan }} - {{ $datau->tgl_akhir_pengerjaan }}
                                                    </td>
                                                </tr>
                                                @endif
                                                <tr>
                                                    <td style="width: 200px; height: 30px;">Keterangan dari Teknisi IT</td>
                                                    <td>:</td>
                                                    <td>
                                                        @if($datau->keterangan != null && $datau->tgl_akhir_pengerjaan != null)
                                                        {{ $datau->keterangan }}
                                                        @else
                                                        -
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Permintaan Terselesaikan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection