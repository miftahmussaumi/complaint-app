@extends('template')

@section('content')
<div class>
    <div class="page-title">
        <div class="title_left">
            <h3>Halaman Permintaan Layanan</h3>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 ">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Masukkan Permintaan Layanan</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form action="{{route('save-laporan')}}" method="POST" id="formLaporan" enctype="multipart/form-data" data-parsley-validate class="form-horizontal form-label-left">
                        {{csrf_field()}}
                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">No Inventaris Aset <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 ">
                                <input type="text" name="no_inv_aset" class="form-control">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Kategori Layanan <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 ">
                                <select name="kat_layanan" id="kat_layanan" onchange="showJenisLayanan()" class="select2_single form-control">
                                    <option value="">Pilih satu</option>
                                    <option value="Throubleshooting">Throubleshooting</option>
                                    <option value="Instalasi">Instalasi</option>
                                </select>
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Jenis Layanan <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6">
                                <select name="jenis_layanan" id="jenis_layanan" onchange="showLainnya()" class="select2_single form-control">
                                    <!-- Opsi dinamis akan dimasukkan di sini menggunakan JavaScript -->
                                </select>
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align"> <span class="required"></span>
                            </label>
                            <div class="col-md-6 col-sm-6 " id="LainnyaInput" style="display: none;">
                                <input type="text" name="layanan_lain" id="LainnyaInput" class="form-control" placeholder="Jenis Layanan Lainnya">
                            </div>
                        </div>
                        <div class="item form-group">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Detail Layanan <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 ">
                                <textarea id="message" class="form-control" name="det_layanan" data-parsley-trigger="keyup" data-parsley-minlength="20" data-parsley-maxlength="100" data-parsley-minlength-message="Come on! You need to enter at least a 20 caracters long comment.." data-parsley-validation-threshold="10"></textarea>
                            </div>
                        </div>

                        <div class="ln_solid"></div>
                        <div class="item form-group">
                            <div class="col-md-6 col-sm-6 offset-md-3">
                                <button class="btn btn-primary" type="button">Cancel</button>
                                <button class="btn btn-primary" type="reset">Reset</button>
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade docs-cropped" id="getCroppedCanvasModal" aria-hidden="true" aria-labelledby="getCroppedCanvasTitle" role="dialog" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="getCroppedCanvasTitle">Cropped</h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <a class="btn btn-primary" id="download" href="javascript:void(0);" download="cropped.png">Download</a>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    function showJenisLayanan() {
        var firstOption = document.getElementById('kat_layanan').value;
        var secondDropdown = document.getElementById('secondDropdown');
        var LainnyaInput = document.getElementById('LainnyaInput');

        // Hapus opsi sebelumnya
        var secondOptionDropdown = document.getElementById('jenis_layanan');
        secondOptionDropdown.innerHTML = "";

        if (firstOption === 'Throubleshooting') {
            var options = ["Aplikasi", "Jaringan", "PC/Laptop", "Printer", "Lainnya"];
            options.forEach(function(option) {
                var opt = document.createElement('option');
                opt.value = option;
                opt.innerHTML = option;
                secondOptionDropdown.appendChild(opt);
            });
            secondDropdown.style.display = 'block';
            LainnyaInput.style.display = 'none';
        } else if (firstOption === 'Instalasi') {
            var options = ["Aplikasi", "Sistem Operasi", "Jaringan", "PC/Laptop", "Printer", "Lainnya"];
            options.forEach(function(option) {
                var opt = document.createElement('option');
                opt.value = option;
                opt.innerHTML = option;
                secondOptionDropdown.appendChild(opt);
            });
            secondDropdown.style.display = 'block';
            LainnyaInput.style.display = 'none';
        } else {
            secondDropdown.style.display = 'none';
            LainnyaInput.style.display = 'none';
        }
    }

    function showLainnya() {
        var secondOption = document.getElementById('jenis_layanan').value;
        var LainnyaInput = document.getElementById('LainnyaInput');

        if (secondOption === 'Lainnya') {
            LainnyaInput.style.display = 'block';
        } else {
            LainnyaInput.style.display = 'none';
        }
    }
</script>

@endsection