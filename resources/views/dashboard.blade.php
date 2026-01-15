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
            $('#train').on('click', function() {
                swal({
                    title: "Notice!",
                    text: "ini akan memulai proses training ulang model K-Fold Cross Validation. Proses ini mungkin memakan waktu beberapa menit. Apakah Anda yakin ingin melanjutkan?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: 'Let\'s Go!',
                    cancelButtonText: 'Cancel',
                }, function(result) {
                    if (result) {
                        swal({
                            title: "Training Dimulai!",
                            text: "Proses training ulang model K-Fold Cross Validation telah dimulai. Silakan tunggu hingga proses selesai.",
                            type: "success",
                        });
                        $.ajax({
                            url: '/nilai-siswa',
                            method: 'GET',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: async function(response) {
                                const accuracy = await kFoldCrossValidation(response
                                    .data, 5);
                                $('#accuracy').text(accuracy + '%');
                                $.ajax({
                                    url: '/method',
                                    method: 'POST',
                                    data: {
                                        accuracy: accuracy,
                                        _token: $('meta[name="csrf-token"]')
                                            .attr('content')
                                    },
                                    success: function(res) {
                                        swal({
                                            title: "Training Selesai!",
                                            text: "Akurasi model: " +
                                                accuracy + "%",
                                            type: "success",
                                        });
                                    },
                                    error: function(xhr, status, error) {
                                        alert('Error saving accuracy: ' +
                                            error);
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
                })
            });

            async function kFoldCrossValidation(data, k = 5) {
                if (!Array.isArray(data) || data.length < k) return 0;
                const shuffled = [...data];
                for (let i = shuffled.length - 1; i > 0; i--) {
                    const j = Math.floor(Math.random() * (i + 1));
                    [shuffled[i], shuffled[j]] = [shuffled[j], shuffled[i]];
                }
                const n = shuffled.length;
                const foldSize = Math.floor(n / k);
                let totalAccuracy = 0;
                const folds = [];
                let start = 0;
                for (let i = 0; i < k; i++) {
                    const end = (i === k - 1) ? n : start + foldSize;
                    folds.push(shuffled.slice(start, end));
                    start = end;
                }
                for (let i = 0; i < k; i++) {
                    await new Promise(resolve => setTimeout(resolve, 0)); // ⬅️ yield async
                    const testSet = folds[i];
                    const trainSet = folds
                        .filter((_, idx) => idx !== i)
                        .flat();
                    const classCount = {};
                    const featureStats = {};
                    trainSet.forEach(row => {
                        const kelas = row.prestasi;
                        classCount[kelas] = (classCount[kelas] || 0) + 1;
                        Object.entries(row).forEach(([f, v]) => {
                            if (['prestasi', 'id', 'nama'].includes(f)) return;
                            featureStats[kelas] ??= {};
                            featureStats[kelas][f] ??= [];
                            featureStats[kelas][f].push(Number(v));
                        });
                    });
                    const nTrain = trainSet.length;
                    const model = {};
                    Object.keys(classCount).forEach(kelas => {
                        model[kelas] = {
                            prior: classCount[kelas] / nTrain,
                            features: {}
                        };
                        Object.entries(featureStats[kelas]).forEach(([f, arr]) => {
                            const mean = arr.reduce((a, b) => a + b, 0) / arr.length;
                            let variance = arr.reduce((s, x) => s + (x - mean) ** 2, 0) / arr
                                .length;
                            if (variance === 0) variance = 1e-6; // ⬅️ anti NaN
                            model[kelas].features[f] = {
                                mean,
                                std: Math.sqrt(variance)
                            };
                        });
                    });
                    let correct = 0;
                    testSet.forEach(row => {
                        let bestClass = null;
                        let bestScore = -Infinity;
                        Object.entries(model).forEach(([kelas, info]) => {
                            let score = Math.log(info.prior);
                            Object.entries(info.features).forEach(([f, stat]) => {
                                const x = Number(row[f]);
                                if (isNaN(x)) return;
                                const {
                                    mean,
                                    std
                                } = stat;
                                score +=
                                    -0.5 * Math.log(2 * Math.PI * std * std) -
                                    ((x - mean) ** 2) / (2 * std * std);
                            });
                            if (score > bestScore) {
                                bestScore = score;
                                bestClass = kelas;
                            }
                        });
                        if (bestClass === row.prestasi) correct++;
                    });
                    totalAccuracy += correct / testSet.length;
                }
                return +(totalAccuracy / k * 100).toFixed(2);
            }
        });
    </script>
@endsection
