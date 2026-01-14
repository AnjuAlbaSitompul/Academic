@extends('layouts.pages.app')
@section('content')
    <div class="row">
        <div class="col-sm-6 col-md-6 col-lg-6 col-xl-9">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Masukkan Data Set</div>
                </div>
                <div class="card-body">
                    <div>
                        <input id="demo" type="file" name="files"
                            accept="image/jpeg, image/png, text/html, application/zip, text/css, text/js" multiple>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-6 col-lg-6 col-xl-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fa fa-comment-o text-success fa-3x"></i>
                    <h6 class="mt-4 mb-2">Investment</h6>
                    <h2 class="mb-2 number-font">80%</h2>
                    <p class="text-muted">Sed ut perspiciatis unde omnis accusantium doloremque</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Siswa</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap border-bottom" id="dataTableSiswa">
                            <thead>
                                <tr>
                                    <th class="wd-15p border-bottom-0">No</th>
                                    <th class="wd-15p border-bottom-0">Nama Siswa</th>
                                    <th class="wd-15p border-bottom-0">Kelas</th>
                                    <th class="wd-15p border-bottom-0"><i class="fe fe-settings"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>



    {{-- modal --}}
    <div class="modal fade" id="largemodal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Input Data Nilai Siswa</h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form class="needs-validation" novalidate>

                    <div class="modal-body">
                        <div class="col-lg-12 col-md-12">
                            <div class="form-row">
                                <div class="col-xl-6 mb-3">
                                    <label for="validationCustom01">Nama Siswa</label>
                                    <input type="text" class="form-control namaSiswa" id="validationCustom01" required>
                                    <div class="valid-feedback">Looks good!</div>
                                </div>
                                <div class="col-xl-6 mb-3">
                                    <label for="validationCustom02">Rata-rata Nilai UAS</label>
                                    <input type="number" pattern="^\d+(\.\d+)?$" class="form-control"
                                        id="validationCustom02" required>
                                    <div class="invalid-feedback">Masukkan Nilai Rata-rata UAS</div>

                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-xl-6 mb-3">
                                    <label for="validationCustom03">Rata-rata Nilai UTS</label>
                                    <input type="number" class="form-control" id="validationCustom03" accept="numeric"
                                        required>
                                    <div class="invalid-feedback">Masukkan Nilai Rata-rata UTS</div>
                                </div>
                                <div class="col-xl-3 mb-3">
                                    <label for="validationCustom05">Nilai UN</label>
                                    <input type="number" class="form-control" id="validationCustom04" accept="numeric"
                                        required>
                                    <div class="invalid-feedback">Masukkan Nilai UN</div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-xl-6 mb-3">
                                    <label for="validationCustom03">Kehadiran %</label>
                                    <input type="number" class="form-control" id="validationCustom05" accept="numeric"
                                        required>
                                    <div class="invalid-feedback">Masukkan Kehadiran</div>
                                </div>
                                <div class="col-xl-3 mb-3">
                                    <label for="validationCustom05">Keterlambatan %</label>
                                    <input type="number" class="form-control" id="validationCustom06" accept="numeric"
                                        required>
                                    <div class="invalid-feedback">Masukkan Keterlambatan</div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-xl-6 mb-3">
                                    <label for="validationCustom03">Status</label>
                                    <input type="text" class="form-control" id="validationCustom07" required>
                                    <div class="invalid-feedback">Masukkan Status</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary" type="submit" id="submitNilai">Save changes</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var table = $('#dataTableSiswa').DataTable({
                ajax: {
                    url: '/siswa/data',
                    type: 'GET',
                    dataSrc: function(json) {
                        return json.data;
                    }
                },
                columns: [{
                        data: function(data, type, row, meta) {
                            return meta.row + 1;
                        },
                        name: 'No'
                    },
                    {
                        data: 'nama_siswa',
                        name: 'Nama Siswa'
                    },
                    {
                        data: 'kelas.nama_kelas',
                        name: 'Nama Kelas'
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `<button class="btn btn-sm btn-primary" id="inputNilai" data-id="${data.id}">Luluskan</button>`;
                        },
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            table.on('click', '#inputNilai', function() {
                var siswaId = $(this).data('id');
                var data = table.row($(this).parents('tr')).data();
                $('.namaSiswa').val(data.nama_siswa);
                $('#largemodal').modal('show');
            });

            $('#largemodal').on('shown.bs.modal', function() {
                $('#validationCustom02').trigger('focus');
            });

            $('.needs-validation').on('submit', function(event) {
                event.preventDefault();
                if (this.checkValidity() === false) {
                    event.stopPropagation();
                }

                $(this).addClass('was-validated');
            });
        });
    </script>
@endsection
