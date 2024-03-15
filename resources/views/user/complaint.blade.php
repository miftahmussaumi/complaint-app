@extends('template')

@section('content')
<div class>
    <div class="page-title">
        <div class="title_left">
            <h3>Welcome <small>in Page Complaints</small></h3>
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
                <h2>List Complaints Users</h2>
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
                                        <th>Tanggal</th>
                                        <th>No. ref</th>
                                        <th>Bussiness Area</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>2013/03/03</td>
                                        <td>Comp/001/IT/002</td>
                                        <td>Manifaktur</td>
                                        <td><span class="badge badge-info">Pengajuan</span></td>
                                        <td><a href="">Detail</a></td>
                                    </tr>
                                    <tr>
                                        <td>2013/03/03</td>
                                        <td>Comp/001/IT/002</td>
                                        <td>Manifaktur</td>
                                        <td><span class="badge badge-warning">Diproses</span></td>
                                        <td><a href="">Detail</a></td>
                                    </tr>
                                    <tr>
                                        <td>2013/03/03</td>
                                        <td>Comp/001/IT/002</td>
                                        <td>Manifaktur</td>
                                        <td><span class="badge badge-secondary">Pengecekan</span></td>
                                        <td><a href="">Detail</a></td>
                                    </tr>
                                    <tr>
                                        <td>2013/03/03</td>
                                        <td>Comp/001/IT/002</td>
                                        <td>Manifaktur</td>
                                        <td><span class="badge badge-success">Selesai</span></td>
                                        <td><a href="">Detail</a></td>
                                    </tr>
                                    <tr>
                                        <td>2013/03/03</td>
                                        <td>Comp/001/IT/002</td>
                                        <td>Manifaktur</td>
                                        <td><span class="badge badge-primary">Acc Manager</span></td>
                                        <td><a href="">Detail</a></td>
                                    </tr>
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