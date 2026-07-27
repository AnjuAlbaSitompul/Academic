@extends('layouts.pages.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Data Prestasi Non-Akademik Siswa</h4>
                    @if ($authUser && $authUser->role === 'admin')
                        <button class="btn btn-sm btn-primary" id="btnTambah">
                            <i class="fe fe-plus"></i> Tambah Data
                        </button>
                    @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="dataTableNonAkademik">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Jumlah Ekskul</th>
                                    <th>Jenis Kegiatan</th>
                                    <th>Tingkat Perlombaan</th>
                                    <th>Perlombaan Diikuti</th>
                                    <th>Kehadiran EK</th>
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

    <div class="modal fade" id="modalNonAkademik">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formNonAkademik" novalidate>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Nama Siswa</label>
                                <input type="text" class="form-control" id="nama" required>
                                <div class="invalid-feedback">Wajib diisi</div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Jumlah Ekstrakurikuler</label>
                                <input type="number" class="form-control" id="jumlah_ekstrakurikuler" min="1" max="5" required>
                                <div class="invalid-feedback">Wajib diisi</div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>Perlombaan Diikuti</label>
                                <input type="number" class="form-control" id="perlombaan_diikuti" min="0" max="10" required>
                                <div class="invalid-feedback">Wajib diisi</div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Jenis Kegiatan</label>
                                <select class="form-control" id="jenis_kegiatan" required>
                                    <option value="">-- Pilih --</option>
                                    <option>Futsal</option>
                                    <option>Bola Voli</option>
                                    <option>Catur</option>
                                    <option>Fashion Show</option>
                                    <option>English Club</option>
                                    <option>Seni Tari</option>
                                    <option>KIR</option>
                                    <option>PMR</option>
                                    <option>Pramuka</option>
                                    <option>Paduan Suara</option>
                                    <option>Rohis</option>
                                    <option>Paskibra</option>
                                </select>
                                <div class="invalid-feedback">Wajib diisi</div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Tingkat Perlombaan</label>
                                <select class="form-control" id="tingkat_perlombaan" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="-">Tidak Mengikuti (-)</option>
                                    <option>Sekolah</option>
                                    <option>Kabupaten</option>
                                    <option>Provinsi</option>
                                    <option>Nasional</option>
                                </select>
                                <div class="invalid-feedback">Wajib diisi</div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Kehadiran EK (%)</label>
                                <input type="number" class="form-control" id="kehadiran_ekstrakurikuler" min="60" max="100" required>
                                <div class="invalid-feedback">Wajib diisi</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Prestasi</label>
                                <select class="form-control" id="prestasi" required>
                                    <option value="">-- Pilih --</option>
                                    <option>YA</option>
                                    <option>TIDAK</option>
                                </select>
                                <div class="invalid-feedback">Wajib diisi</div>
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
                $('#formNonAkademik [required]').each(function() {
                    if (!$(this).val()) {
                        $(this).addClass('is-invalid');
                        valid = false;
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });
                return valid;
            }

            const table = $('#dataTableNonAkademik').DataTable({
                ajax: {
                    url: '/nilai-non-akademik',
                    dataSrc: 'data'
                },
                columns: [{
                        data: null,
                        render: (d, t, r, m) => m.row + 1
                    },
                    { data: 'nama' },
                    { data: 'jumlah_ekstrakurikuler' },
                    { data: 'jenis_kegiatan' },
                    { data: 'tingkat_perlombaan' },
                    { data: 'perlombaan_diikuti' },
                    { data: 'kehadiran_ekstrakurikuler' },
                    { data: 'prestasi' },
                    {
                        data: null,
                        render: () => `
                    <button class="btn btn-sm btn-warning edit">Edit</button>
                    <button class="btn btn-sm btn-danger delete">Hapus</button>
                `
                    }
                ]
            });

            $('#btnTambah').click(function() {
                $('#modalTitle').text('Tambah Data Non-Akademik');
                $('#formNonAkademik')[0].reset();
                $('#btnInsert').removeClass('d-none');
                $('#btnUpdate').addClass('d-none');
                $('#modalNonAkademik').modal('show');
            });

            $('#btnInsert').click(function() {
                if (!validate()) return;
                $.post('/nilai-non-akademik', {
                    _token: '{{ csrf_token() }}',
                    nama: $('#nama').val(),
                    jumlah_ekstrakurikuler: $('#jumlah_ekstrakurikuler').val(),
                    jenis_kegiatan: $('#jenis_kegiatan').val(),
                    tingkat_perlombaan: $('#tingkat_perlombaan').val(),
                    perlombaan_diikuti: $('#perlombaan_diikuti').val(),
                    kehadiran_ekstrakurikuler: $('#kehadiran_ekstrakurikuler').val(),
                    prestasi: $('#prestasi').val()
                }, function() {
                    $('#modalNonAkademik').modal('hide');
                    table.ajax.reload();
                });
            });

            $('#dataTableNonAkademik').on('click', '.edit', function() {
                const d = table.row($(this).closest('tr')).data();
                $('#modalTitle').text('Edit Data Non-Akademik');
                $('#nama').val(d.nama);
                $('#jumlah_ekstrakurikuler').val(d.jumlah_ekstrakurikuler);
                $('#jenis_kegiatan').val(d.jenis_kegiatan);
                $('#tingkat_perlombaan').val(d.tingkat_perlombaan);
                $('#perlombaan_diikuti').val(d.perlombaan_diikuti);
                $('#kehadiran_ekstrakurikuler').val(d.kehadiran_ekstrakurikuler);
                $('#prestasi').val(d.prestasi);
                $('#btnUpdate').data('id', d.id).removeClass('d-none');
                $('#btnInsert').addClass('d-none');
                $('#modalNonAkademik').modal('show');
            });

            $('#btnUpdate').click(function() {
                if (!validate()) return;
                $.ajax({
                    url: '/nilai-non-akademik/' + $(this).data('id'),
                    method: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                        nama: $('#nama').val(),
                        jumlah_ekstrakurikuler: $('#jumlah_ekstrakurikuler').val(),
                        jenis_kegiatan: $('#jenis_kegiatan').val(),
                        tingkat_perlombaan: $('#tingkat_perlombaan').val(),
                        perlombaan_diikuti: $('#perlombaan_diikuti').val(),
                        kehadiran_ekstrakurikuler: $('#kehadiran_ekstrakurikuler').val(),
                        prestasi: $('#prestasi').val()
                    },
                    success: function() {
                        $('#modalNonAkademik').modal('hide');
                        table.ajax.reload();
                    }
                });
            });

            $('#dataTableNonAkademik').on('click', '.delete', function() {
                deleteId = table.row($(this).closest('tr')).data().id;
                $('#modalDelete').modal('show');
            });

            $('#confirmDelete').click(function() {
                $.ajax({
                    url: '/nilai-non-akademik/' + deleteId,
                    method: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function() {
                        $('#modalDelete').modal('hide');
                        table.ajax.reload();
                    }
                });
            });
        });
    </script>
@endsection
