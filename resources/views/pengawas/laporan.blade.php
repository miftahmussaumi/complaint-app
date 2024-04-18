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
                    </div>
                    <div class="table-responsive">
                        <table style="color: #2D3134;" class="table table-striped table-bordered zero-configuration">
                            <thead>
                                <tr>
                                    <th>Tanggal Permintaan</th>
                                    <th>Pelapor</th>
                                    <th>No Referensi</th>
                                    <th>No Inventaris</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Teknisi</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lap as $data)
                                <tr>
                                    <td>{{ $data->tgl_masuk }}</td>
                                    <td>{{ $data->nama_pelapor }}</td>
                                    <td>{{ $data->lap_no_ref }}</td>
                                    <td>{{ $data->no_inv_aset }}</td>
                                    <td>{{ $data->tgl_selesai }}</td>
                                    <td>
                                        @if($data->id_teknisi != null)
                                        {{$data->nama_teknisi}}
                                        @else
                                        <i>Teknisi belum dipilih</i>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{url('detail-laporan',$data->id)}}"><button class="btn btn-warning btn-sm"><i class="fa fa-eye"></i></button></a>
                                        <a href="{{url('cetak-laporan',$data->id)}}"><button class="btn btn-primary btn-sm"><i class="fa fa-file-pdf-o"></i></button></a>
                                    </td>
                                </tr>
                                @endforeach
                        </table>
                        <!-- ========= MODAL ========= -->

                        <!-- ========= END MODAL ========= -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection