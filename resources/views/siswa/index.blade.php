@extends('layouts.pages.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title mb-0">Data Siswa</h3>
        </div>
        <div class="card-body pt-4">
            <div class="table-responsive">
                <table id="data-table" class="table table-bordered text-nowrap mb-2" style="text-align: center">
                    <thead></thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-2">
                <button class="btn btn-sm btn-secondary" id="prev">Mundur</button>
                <span id="page-info"></span>
                <button class="btn btn-sm btn-secondary" id="next">Maju</button>
            </div>
        </div>
    </div>

    @include('siswa.table-js')

    <form method="get" class="mt-3" id="form-latih">
        <button class="btn btn-primary btn-sm" name="latih" value="1" id="btn-latih">
            Latih K-Fold
        </button>
        <span id="loading" style="display:none; margin-left:10px;">
            <i class="fa fa-spinner fa-spin"></i> Sedang menghitung...
        </span>
    </form>

    @php
        $hasilKFold = [];
        $totalAkurasi = 0;
        $k = 5;
        if (request()->has('latih')) {
            $path = public_path('assets/data/data-siswa.csv');
            if (file_exists($path)) {
                $rows = array_map('str_getcsv', file($path));
                $header = array_shift($rows);
                $data = [];
                foreach ($rows as $row) {
                    $data[] = array_combine($header, $row);
                }
                shuffle($data);
                $foldSize = ceil(count($data) / $k);
                $folds = array_chunk($data, $foldSize);
                for ($i = 0; $i < $k; $i++) {
                    $dataUji = $folds[$i];
                    $dataLatih = [];
                    foreach ($folds as $j => $f) {
                        if ($j !== $i) {
                            $dataLatih = array_merge($dataLatih, $f);
                        }
                    }
                    $kelasProb = [];
                    $fiturStats = [];
                    foreach ($dataLatih as $row) {
                        $kelas = $row['prestasi'];
                        if (!isset($kelasProb[$kelas])) {
                            $kelasProb[$kelas] = 0;
                        }
                        $kelasProb[$kelas]++;
                        foreach ($row as $f => $v) {
                            if ($f === 'prestasi' || $f === 'id') {
                                continue;
                            }
                            $fiturStats[$kelas][$f][] = (float) $v;
                        }
                    }
                    $nLatih = count($dataLatih);
                    foreach ($kelasProb as $kelas => $c) {
                        $kelasProb[$kelas] = $c / $nLatih; // prior
                        foreach ($fiturStats[$kelas] as $f => $arr) {
                            $mean = array_sum($arr) / count($arr);
                            $std =
                                count($arr) > 1
                                    ? sqrt(array_sum(array_map(fn($x) => pow($x - $mean, 2), $arr)) / count($arr))
                                    : 1;
                            $fiturStats[$kelas][$f] = ['mean' => $mean, 'std' => $std];
                        }
                    }

                    $benar = 0;
                    $prediksiUji = [];
                    $scoreUji = [];
                    foreach ($dataUji as $row) {
                        $scores = [];
                        foreach ($kelasProb as $kelas => $prior) {
                            $scores[$kelas] = log($prior);
                            foreach ($row as $f => $v) {
                                if ($f === 'prestasi' || $f === 'id') {
                                    continue;
                                }
                                $mean = $fiturStats[$kelas][$f]['mean'];
                                $std = $fiturStats[$kelas][$f]['std'] ?: 1;
                                $x = (float) $v;
                                $scores[$kelas] +=
                                    -0.5 * log(2 * M_PI * $std * $std) - pow($x - $mean, 2) / (2 * $std * $std);
                            }
                        }
                        $pred = array_keys($scores, max($scores))[0];
                        $prediksiUji[] = $pred;
                        $scoreUji[] = $scores;
                        if ($pred === $row['prestasi']) {
                            $benar++;
                        }
                    }
                    $akurasi = $benar / count($dataUji);
                    $totalAkurasi += $akurasi;
                    $hasilKFold[] = [
                        'fold' => $i + 1,
                        'jumlah_latih' => count($dataLatih),
                        'jumlah_uji' => count($dataUji),
                        'prediksi' => $prediksiUji,
                        'akurasi' => round($akurasi * 100, 2),
                        'data_uji' => $dataUji,
                        'score' => $scoreUji,
                    ];
                }
                $rataAkurasi = round(($totalAkurasi / $k) * 100, 2);
            } else {
                $errorCsv = 'File CSV tidak ditemukan!';
            }
        }
    @endphp

    @if (!empty($errorCsv))
        <div class="alert alert-danger mt-2">{{ $errorCsv }}</div>
    @else
        @if (!empty($hasilKFold))
            <hr>
            <h5 class="mt-3">Hasil K-Fold Cross Validation (K={{ $k }})</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-sm" style="text-align:center">
                    <thead>
                        <tr>
                            <th>Fold</th>
                            <th>Jumlah Data Latih</th>
                            <th>Jumlah Data Uji</th>
                            <th>Prediksi Benar</th>
                            <th>Akurasi (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($hasilKFold as $fold)
                            @php
                                $prediksiBenar = 0;
                                foreach ($fold['data_uji'] as $index => $uji) {
                                    if ($fold['prediksi'][$index] === $uji['prestasi']) {
                                        $prediksiBenar++;
                                    }
                                }
                            @endphp
                            <tr>
                                <td>{{ $fold['fold'] }}</td>
                                <td>{{ $fold['jumlah_latih'] }}</td>
                                <td>{{ $fold['jumlah_uji'] }}</td>
                                <td>{{ $prediksiBenar }}</td>
                                <td>{{ round(($prediksiBenar / $fold['jumlah_uji']) * 100, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @foreach ($hasilKFold as $fold)
                <div class="card mt-3">
                    <div class="card-header">
                        <strong>Detail Prediksi Fold {{ $fold['fold'] }}</strong>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm" style="text-align:center">
                                <thead>
                                    <tr>
                                        <th>Nama Siswa</th>
                                        <th>Prestasi Aktual</th>
                                        <th>Prediksi</th>
                                        <th>Score (Log Akurasi tiap Kelas)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($fold['data_uji'] as $index => $uji)
                                        <tr>
                                            <td>{{ $uji['id'] }}</td>
                                            <td>{{ $uji['prestasi'] }}</td>
                                            <td>{{ $fold['prediksi'][$index] }}</td>
                                            <td>
                                                @foreach ($fold['score'][$index] as $kelas => $s)
                                                    {{ $kelas }}: {{ round($s, 4) }}<br>
                                                @endforeach
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="alert alert-info mt-3">
                <strong>Total Rata-Rata Akurasi K-Fold: {{ $rataAkurasi }}%</strong>
            </div>
        @endif
    @endif
@endsection

@section('scripts')
    <script>
        document.getElementById('form-latih').addEventListener('submit', function() {
            document.getElementById('btn-latih').disabled = true;
            document.getElementById('loading').style.display = 'inline';
        });
    </script>
@endsection
