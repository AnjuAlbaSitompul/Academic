@extends('layouts.pages.app')

@section('loader')
    @include('partials.loader')
@endsection
@section('content')
    <!-- ROW-1 -->
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                    <div class="card overflow-hidden">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="mt-2">
                                    <h6 class="">Total Users</h6>
                                    <h2 class="mb-0 number-font">{{ $user }}</h2>
                                </div>
                                <div class="ms-auto">
                                    <div class="chart-wrapper mt-1">
                                        <canvas id="saleschart" class="h-8 w-9 chart-dropshadow"></canvas>
                                    </div>
                                </div>
                            </div>
                            <span class="text-muted fs-12"><span class="text-secondary"><i
                                        class="fe fe-arrow-up-circle  text-secondary"></i>{{ $lastUserUpdate ? $lastUserUpdate->format('l, F j, Y') : 'No Data' }}</span></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                    <div class="card overflow-hidden">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="mt-2">
                                    <h6 class="">Accuracy K-Fold</h6>
                                    <h2 class="mb-0 number-font" id="accuracy">{{ $accuracy }}%</h2>
                                </div>
                                <div class="ms-auto">
                                    <div class="chart-wrapper mt-1">
                                        <canvas id="leadschart" class="h-8 w-9 chart-dropshadow"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="text-muted fs-12">
                                    <span class="text-secondary"><i class="fe fe-calendar text-secondary"></i>
                                        {{ $lastMethodUpdate ? $lastMethodUpdate->format('l, F j, Y') : 'No Data' }}</span>
                                </span>
                                @if ($authUser && $authUser->role === 'admin')
                                    <button class="btn btn-sm btn-primary" id="train">
                                        Train
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                    <div class="card overflow-hidden">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="mt-2">
                                    <h6 class="">Siswa</h6>
                                    <h2 class="mb-0 number-font">{{ $siswa }}</h2>
                                </div>
                                <div class="ms-auto">
                                    <div class="chart-wrapper mt-1">
                                        <canvas id="profitchart" class="h-8 w-9 chart-dropshadow"></canvas>
                                    </div>
                                </div>
                            </div>
                            <span class="text-muted fs-12">
                                <span class="text-secondary"><i class="fe fe-calendar text-secondary"></i>
                                    {{ $lastSiswaUpdate ? $lastSiswaUpdate->format('l, F j, Y') : 'No Data' }}</span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xl-3">
                    <div class="card overflow-hidden">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="mt-2">
                                    <h6 class="">Jumlah Lulusan</h6>
                                    <h2 class="mb-0 number-font">{{ $totalNilai }}</h2>
                                </div>
                                <div class="ms-auto">
                                    <div class="chart-wrapper mt-1">
                                        <canvas id="costchart" class="h-8 w-9 chart-dropshadow"></canvas>
                                    </div>
                                </div>
                            </div>
                            <span class="text-muted fs-12">
                                <span class="text-secondary"><i class="fe fe-calendar text-secondary"></i>
                                    {{ $lastNilaiUpdate ? $lastNilaiUpdate->format('l, F j, Y') : 'No Data' }}
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ROW-1 END -->

    <script>
        $(document).ready(function() {
            // Tombol Train: jalankan K-Fold Cross Validation (K=10) Decision Tree C4.5
            // untuk model prestasi akademik, lalu simpan accuracy ke tabel methods.
            $('#train').on('click', function() {
                swal({
                    title: "Notice!",
                    text: "Ini akan memulai proses training model Decision Tree (C4.5) dengan 10-Fold Cross Validation. Proses ini mungkin memakan waktu beberapa menit. Lanjutkan?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: 'Let\'s Go!',
                    cancelButtonText: 'Cancel',
                }, function(result) {
                    if (result) {
                        swal({
                            title: "Training Dimulai!",
                            text: "Proses training model Decision Tree C4.5 (10-Fold Cross Validation) telah dimulai. Silakan tunggu hingga proses selesai.",
                            type: "success",
                        });
                        $.ajax({
                            url: '/nilai-siswa',
                            method: 'GET',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                // K-Fold Cross Validation K=10 dengan algoritma C4.5
                                const data = response.data.map(function (r) {
                                    return {
                                        nilai_uas: Number(r.nilai_uas),
                                        nilai_uts: Number(r.nilai_uts),
                                        nilai_un: Number(r.nilai_un),
                                        kehadiran: Number(r.kehadiran),
                                        keterlambatan: Number(r.keterlambatan),
                                        prestasi: r.prestasi
                                    };
                                });
                                const res = DecisionTree.kFoldCrossValidation(
                                    data, DecisionTree.CONFIG.akademik, 10);
                                const accuracy = +(res.avg.accuracy * 100).toFixed(2);
                                $('#accuracy').text(accuracy + '%');
                                $.ajax({
                                    url: '/method',
                                    method: 'POST',
                                    data: {
                                        accuracy: accuracy,
                                        _token: $('meta[name="csrf-token"]').attr('content')
                                    },
                                    success: function(res) {
                                        swal({
                                            title: "Training Selesai!",
                                            text: "Akurasi model Decision Tree C4.5 (rata-rata 10 fold): " + accuracy + "%",
                                            type: "success",
                                        }, function () {
                                            window.location.href = '/evaluasi';
                                        });
                                    },
                                    error: function(xhr, status, error) {
                                        alert('Error saving accuracy: ' + error);
                                    }
                                });
                            },
                            error: function(xhr, status, error) {
                                swal({
                                    title: "Error!",
                                    text: "Terjadi kesalahan saat memulai proses training. Silakan coba lagi.",
                                    type: "error",
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
