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
                                        <th>Detail Layanan</th>
                                        <th>Periode Pengerjaan</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dtLap as $data)
                                    <tr>
                                        <td>{{ $data->tgl_masuk }}</td>
                                        <td>{{ $data->no_inv_aset }}</td>
                                        <td>{{ $data->kat_layanan }}</td>
                                        <td>{{ $data->jenis_layanan }}</td>
                                        <td>{{ $data->det_layanan }}</td>
                                        <td>
                                            @if($data->tgl_awal_pengerjaan == null && $data->tgl_akhir_pengerjaan == null)
                                            <em>menunggu diproses</em>
                                            @else
                                            {{ $data->tgl_awal_pengerjaan }} - {{ $data->tgl_akhir_pengerjaan }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($data->status_laporan == 'Pengajuan')
                                            <span class="badge badge-dark">Pengajuan</span>
                                            @elseif($data->status_laporan == 'Diproses')
                                            <span class="badge badge-info">Diproses</span>
                                            @elseif($data->status_laporan == 'PengecekanU')
                                            <span class="badge badge-warning">Pengecekan</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection