@extends('template')
@section('content')
<div class>
    <div class="page-title">
        <div class="title_left">
            <h3>Welcome <small>in Detail Complaints</small></h3>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_content">
                <br />
                <form class="form-horizontal form-label-left">
                    <div class="form-group row ">
                        <label class="control-label col-md-3 col-sm-3 ">No Referensi</label>
                        <div class="col-md-9 col-sm-9 ">
                            <input type="text" class="form-control" disabled value="IT/COMP/0012">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-3 ">Tanggal </label>
                        <div class="col-md-9 col-sm-9 ">
                            <input type="text" class="form-control" disabled value="02/11/2024">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-3 ">Permintaan Layanan dari</label>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-3 ">Nama <span class="required">*</span>
                        </label>
                        <div class="col-md-9 col-sm-9 ">
                            <input type="text" class="form-control" disabled value="Andreas Kamila">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-3 ">Unit <span class="required">*</span>
                        </label>
                        <div class="col-md-9 col-sm-9 ">
                            <input type="text" class="form-control" disabled value="Manufaktur">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-3 ">Telepon <span class="required">*</span>
                        </label>
                        <div class="col-md-9 col-sm-9 ">
                            <input type="text" class="form-control" disabled value="08xxxxxxxx">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-3 ">Email <span class="required">*</span>
                        </label>
                        <div class="col-md-9 col-sm-9 ">
                            <input type="text" class="form-control" disabled value="xxxx@gmail.com">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-md-3 col-sm-3 ">Permasalahan <span class="required">*</span>
                        </label>
                    </div>
                    <div class="form-group row">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tipe Permasalahan</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">1</th>
                                    <td>Mark</td>
                                    <td>Otto</td>
                                    <td>@mdo</td>
                                </tr>
                                <tr>
                                    <th scope="row">2</th>
                                    <td>Jacob</td>
                                    <td>Thornton</td>
                                    <td>@fat</td>
                                </tr>
                                <tr>
                                    <th scope="row">3</th>
                                    <td>Larry</td>
                                    <td>the Bird</td>
                                    <td>@twitter</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection