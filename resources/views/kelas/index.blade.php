@extends('layouts.pages.app')
@section('content')
    <div class="col-md-12 col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Daftar Kelas</h4>
            </div>
            <div class="card-body">
                <form class="form-horizontal">
                    <div class=" row mb-4">
                        <label for="inputName" class="col-md-3 form-label">Nama Kelas</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="inputName" placeholder="Nama Kelas"
                                autocomplete="name">
                        </div>
                    </div>
                    <div class="mb-0 mt-4 row justify-content-end">
                        <div class="col-md-9">
                            <button class="btn btn-primary" id="simpanKelas">Simpan</button>
                            <button class="btn btn-secondary" id="updateKelas" style="display:none">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Kelas</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-nowrap border-bottom" id="dataTable1">
                        <thead>
                            <tr>
                                <th class="wd-15p border-bottom-0">No</th>
                                <th class="wd-15p border-bottom-0">Nama Kelas</th>
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

    {{-- modal --}}
    <div class="modal fade" id="modaldemo4">
        <div class="modal-dialog modal-dialog-centered text-center " role="document">
            <div class="modal-content tx-size-sm">
                <div class="modal-body text-center p-4 pb-5">
                    <button aria-label="Close" class="btn-close position-absolute" data-bs-dismiss="modal"><span
                            aria-hidden="true">&times;</span></button>
                    <i class="icon fs-70  lh-1 my-5 d-inline-block" id="modalIcon"></i>
                    <h4 class=" tx-semibold" id="modalText"></h4><button aria-label="Close" class="btn btn-success pd-x-25"
                        id="modalButton">Continue</button>
                </div>
            </div>
        </div>
    </div>

    <!-- DATA TABLE JS-->

    <script>
        $(document).ready(function() {
            // Table Data Kelas
            let table = $('#dataTable1').DataTable({
                ajax: {
                    url: '/kelas/data',
                    type: 'GET',
                    datasrc: function(json) {
                        return json.data;
                    }
                },
                columns: [{
                        data: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        name: 'no'
                    },
                    {
                        data: 'nama_kelas',
                        name: 'Nama Kelas'
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `<button class="btn btn-sm btn-primary" id="editKelas" data-id="${data.id}"><i class="fe fe-edit"></i></button>
                                    <button class="btn btn-sm btn-danger" id="deleteKelas" data-id="${data.id}"><i class="fe fe-trash"></i></button>`;
                        },
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            function showModal(isSuccess, message, showButton = false) {
                const modalIcon = $('#modalIcon');
                modalIcon.removeClass(isSuccess ? 'text-danger  icon-close' : 'text-success icon-check');
                modalIcon.addClass(isSuccess ? 'text-success icon-check' : 'text-danger icon-close');
                showButton ? $('#modalButton').show() : $('#modalButton').hide();
                $('#modalText').text(message);
                $('#modaldemo4').modal('show');
            }

            // Delete Kelas
            $('#dataTable1').on('click', '#deleteKelas', function() {
                let kelasId = $(this).data('id');
                $.ajax({
                    url: '/kelas/' + kelasId,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        table.ajax.reload();
                        showModal(true, 'Kelas berhasil dihapus.');
                    },
                    error: function(xhr) {
                        showModal(false, 'Terjadi kesalahan saat menghapus kelas.');
                    }
                });

            });

            // on Edit Kelas
            $('#dataTable1').on('click', '#editKelas', function() {
                let kelasId = $(this).data('id');
                let nama = table.row($(this).parents('tr')).data().nama_kelas
                $('#inputName').val(nama);
                $('#updateKelas').data('id', kelasId);
                $('#updateKelas').fadeIn();
                $('#simpanKelas').fadeOut();
            });

            // on Update Kelas
            $('#updateKelas').on('click', function(event) {
                event.preventDefault();
                let kelasId = $(this).data('id');
                let namaKelas = $('#inputName').val();

                $.ajax({
                    url: '/kelas/' + kelasId,
                    method: 'PUT',
                    data: {
                        nama_kelas: namaKelas,
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        table.ajax.reload();
                        $('#inputName').val('');
                        $('#updateKelas').fadeOut();
                        $('#simpanKelas').fadeIn();
                        showModal(true, 'Kelas berhasil diperbarui.');
                    },
                    error: function(xhr) {
                        showModal(false, 'Terjadi kesalahan saat memperbarui kelas.');
                    }
                });
            });


            // on Save Kelas
            $('#simpanKelas').on('click', function(event) {
                event.preventDefault();
                let namaKelas = $('#inputName').val();

                $.ajax({
                    url: '/kelas',
                    method: 'POST',
                    data: {
                        nama_kelas: namaKelas,
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        table.ajax.reload();
                        $('#inputName').val('');
                        showModal(true, 'Kelas berhasil disimpan.');
                    },
                    error: function(xhr) {
                        showModal(false, 'Terjadi kesalahan saat menyimpan kelas.');
                    }
                });
            });
        });
    </script>
@endsection
