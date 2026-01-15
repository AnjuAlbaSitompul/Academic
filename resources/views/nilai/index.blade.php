@extends('layouts.pages.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Data Siswa</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered" id="dataTableSiswa">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Data Nilai</h4>
                    @if ($authUser && $authUser->role === 'admin')
                        <button class="btn btn-sm btn-success" id="btnUpload">
                            Upload CSV Nilai
                        </button>
                    @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="dataTableNilai">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>UAS</th>
                                    <th>UTS</th>
                                    <th>UN</th>
                                    <th>Kehadiran</th>
                                    <th>Keterlambatan</th>
                                    <th>Prestasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalUpload" tabindex="-1" aria-labelledby="modalUploadLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalUploadLabel">Upload CSV Nilai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formUpload" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="fileCsv" class="form-label">Pilih File CSV</label>
                            <input type="file" name="file" id="fileCsv" class="form-control" accept=".csv"
                                required>
                            <div class="form-text">
                                Format: <code>nama,nilai_uas,nilai_uts,nilai_un,kehadiran,keterlambatan,prestasi</code>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="button" class="btn btn-success" id="btnConfirmUpload">
                        Upload
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalNilai">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formNilai" novalidate>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Nama Siswa</label>
                                <input type="text" class="form-control" id="nama" readonly>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Nilai UAS</label>
                                <input type="number" class="form-control" id="nilai_uas" required>
                                <div class="invalid-feedback">Wajib diisi</div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Nilai UTS</label>
                                <input type="number" class="form-control" id="nilai_uts" required>
                                <div class="invalid-feedback">Wajib diisi</div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Nilai UN</label>
                                <input type="number" class="form-control" id="nilai_un" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Kehadiran</label>
                                <input type="number" class="form-control" id="kehadiran" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Keterlambatan</label>
                                <input type="number" class="form-control" id="keterlambatan" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Prestasi</label>
                                <select class="form-control" id="prestasi" required>
                                    <option value="">Pilih</option>
                                    <option value="YA">YA</option>
                                    <option value="TIDAK">TIDAK</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary d-none" id="btnInsert">Simpan</button>
                    <button class="btn btn-warning d-none" id="btnUpdate">Update</button>
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalDelete">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <p>Yakin ingin menghapus data ini?</p>
                    <button class="btn btn-danger" id="confirmDelete">Ya</button>
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(function() {
            let deleteId = null;

            function validate() {
                let valid = true;
                $('#formNilai [required]').each(function() {
                    if (!$(this).val()) {
                        $(this).addClass('is-invalid');
                        valid = false;
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });
                return valid;
            }
            let tableSiswa = $('#dataTableSiswa').DataTable({
                ajax: {
                    url: '/siswa/data',
                    dataSrc: 'data'
                },
                columns: [{
                        data: null,
                        render: (d, t, r, m) => m.row + 1
                    },
                    {
                        data: 'nama_siswa'
                    },
                    {
                        data: 'kelas.nama_kelas'
                    },
                    {
                        data: null,
                        render: () =>
                            `<button class="btn btn-sm btn-primary luluskan">Luluskan</button>`
                    }
                ]
            });
            let tableNilai = $('#dataTableNilai').DataTable({
                ajax: {
                    url: '/nilai-siswa',
                    dataSrc: 'data'
                },
                columns: [{
                        data: null,
                        render: (d, t, r, m) => m.row + 1
                    },
                    {
                        data: 'nama'
                    },
                    {
                        data: 'nilai_uas'
                    },
                    {
                        data: 'nilai_uts'
                    },
                    {
                        data: 'nilai_un'
                    },
                    {
                        data: 'kehadiran'
                    },
                    {
                        data: 'keterlambatan'
                    },
                    {
                        data: 'prestasi'
                    },
                    {
                        data: null,
                        render: () => `
                <button class="btn btn-sm btn-warning edit">Edit</button>
                <button class="btn btn-sm btn-danger delete">Hapus</button>
            `
                    }
                ]
            });
            $('#dataTableSiswa').on('click', '.luluskan', function() {
                let d = tableSiswa.row($(this).closest('tr')).data();
                $('#modalTitle').text('Input Nilai');
                $('#formNilai')[0].reset();
                $('#nama').val(d.nama_siswa);
                $('#btnInsert').data('id', d.id).removeClass('d-none');
                $('#btnUpdate').addClass('d-none');
                $('#modalNilai').modal('show');
            });
            $('#btnUpload').click(function() {
                $('#formUpload')[0].reset();
                $('#modalUpload').modal('show');
            });
            $('#btnConfirmUpload').click(function() {
                let formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('file', $('#fileCsv')[0].files[0]);
                $.ajax({
                    url: '/nilai-siswa/upload',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        alert(res.message);
                        $('#modalUpload').modal('hide');
                        tableNilai.ajax.reload();
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON.message ?? 'Upload gagal');
                    }
                });
            });

            $('#btnInsert').click(function() {
                if (!validate()) return;
                $.post('/nilai-siswa', {
                    _token: '{{ csrf_token() }}',
                    siswa_id: $(this).data('id'),
                    nama: $('#nama').val(),
                    nilai_uas: $('#nilai_uas').val(),
                    nilai_uts: $('#nilai_uts').val(),
                    nilai_un: $('#nilai_un').val(),
                    kehadiran: $('#kehadiran').val(),
                    keterlambatan: $('#keterlambatan').val(),
                    prestasi: $('#prestasi').val()
                }, function() {
                    $('#modalNilai').modal('hide');
                    tableNilai.ajax.reload();
                    tableSiswa.ajax.reload();
                });
            });

            $('#dataTableNilai').on('click', '.edit', function() {
                let d = tableNilai.row($(this).closest('tr')).data();
                $('#modalTitle').text('Edit Nilai');
                $('#nama').val(d.nama);
                $('#nilai_uas').val(d.nilai_uas);
                $('#nilai_uts').val(d.nilai_uts);
                $('#nilai_un').val(d.nilai_un);
                $('#kehadiran').val(d.kehadiran);
                $('#keterlambatan').val(d.keterlambatan);
                $('#prestasi').val(d.prestasi);
                $('#btnUpdate').data('id', d.id).removeClass('d-none');
                $('#btnInsert').addClass('d-none');
                $('#modalNilai').modal('show');
            });
            $('#btnUpdate').click(function() {
                if (!validate()) return;
                $.ajax({
                    url: '/nilai-siswa/' + $(this).data('id'),
                    method: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                        nama: $('#nama').val(),
                        nilai_uas: $('#nilai_uas').val(),
                        nilai_uts: $('#nilai_uts').val(),
                        nilai_un: $('#nilai_un').val(),
                        kehadiran: $('#kehadiran').val(),
                        keterlambatan: $('#keterlambatan').val(),
                        prestasi: $('#prestasi').val()
                    },
                    success: function() {
                        $('#modalNilai').modal('hide');
                        tableNilai.ajax.reload();
                    }
                });
            });
            $('#dataTableNilai').on('click', '.delete', function() {
                deleteId = tableNilai.row($(this).closest('tr')).data().id;
                $('#modalDelete').modal('show');
            });
            $('#confirmDelete').click(function() {
                $.ajax({
                    url: '/nilai-siswa/' + deleteId,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        $('#modalDelete').modal('hide');
                        tableNilai.ajax.reload();
                    }
                });
            });
        });
    </script>
@endsection
