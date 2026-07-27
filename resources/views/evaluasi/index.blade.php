@extends('layouts.pages.app')
@section('content')
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-1">Evaluasi Model Decision Tree C4.5 (10-Fold Cross Validation)</h4>
                    <p class="text-muted mb-0 fs-13">
                        Halaman ini menyajikan hasil penerapan algoritma Decision Tree C4.5 dengan
                        validasi 10-Fold Cross Validation untuk prediksi prestasi akademik dan
                        prestasi non-akademik siswa MAS Hidayatul Islam. Dataset akademik terdiri dari
                        <strong>{{ $totalAkademik }}</strong> catatan dan dataset non-akademik terdiri dari
                        <strong>{{ $totalNonAkademik }}</strong> catatan.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Navigasi tab antar model --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header p-0">
                    <ul class="nav nav-tabs" id="modelTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="akademik-tab" data-bs-toggle="tab" href="#akademik"
                                role="tab">
                                Prestasi Akademik
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="nonAkademik-tab" data-bs-toggle="tab" href="#nonAkademik"
                                role="tab">
                                Prestasi Non-Akademik
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">

                        {{-- ================== MODEL AKADEMIK ================== --}}
                        <div class="tab-pane fade show active" id="akademik" role="tabpanel">
                            {{-- Ringkasan metrik --}}
                            <div class="row mb-3">
                                <div class="col-6 col-md-3">
                                    <div class="card text-center bg-primary-transparent">
                                        <div class="card-body">
                                            <h6 class="text-muted">Accuracy</h6>
                                            <h3 class="mb-0" id="aka-acc">-</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="card text-center bg-success-transparent">
                                        <div class="card-body">
                                            <h6 class="text-muted">Precision</h6>
                                            <h3 class="mb-0" id="aka-prec">-</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="card text-center bg-warning-transparent">
                                        <div class="card-body">
                                            <h6 class="text-muted">Recall</h6>
                                            <h3 class="mb-0" id="aka-rec">-</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="card text-center bg-danger-transparent">
                                        <div class="card-body">
                                            <h6 class="text-muted">F1-score</h6>
                                            <h3 class="mb-0" id="aka-f1">-</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 4.2.1.2 Penerapan algoritma: parameter model + info gain --}}
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="card h-100">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">Parameter Model</h5>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-sm table-bordered mb-0">
                                                <tbody>
                                                    <tr>
                                                        <th>Algoritma</th>
                                                        <td>Decision Tree (C4.5)</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Kriteria Pemisahan</th>
                                                        <td>Information Gain</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Nilai K</th>
                                                        <td>10</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Jumlah Data</th>
                                                        <td>587</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Kelas Target</th>
                                                        <td>YA / TIDAK</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Distribusi Kelas</th>
                                                        <td>YA: 47 (8,01%)<br>TIDAK: 540 (91,99%)</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="card h-100">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">Information Gain Seluruh Atribut (Tabel 4.3)</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped mb-0" id="aka-infoGain">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Atribut</th>
                                                            <th>Titik Pemisahan</th>
                                                            <th>Info(atribut)</th>
                                                            <th>Information Gain</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 4.1.2.2 Visualisasi pohon keputusan --}}
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Visualisasi Struktur Pohon Keputusan Akademik</h5>
                                </div>
                                <div class="card-body">
                                    <div id="aka-tree" style="width:100%;height:420px;"></div>
                                </div>
                            </div>

                            {{-- Aturan if-then (Tabel 4.4) --}}
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Aturan If-Then Hasil Pohon Keputusan (Tabel 4.4)</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped mb-0" id="aka-rules">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Aturan If-Then</th>
                                                    <th>Jumlah Data</th>
                                                    <th>Label</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            {{-- 4.2.2 Distribusi data latih/uji (Tabel 4.7) --}}
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Distribusi Data Latih dan Data Uji per Fold (Tabel 4.7)</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped mb-0" id="aka-split">
                                            <thead>
                                                <tr>
                                                    <th>Fold</th>
                                                    <th>Data Latih</th>
                                                    <th>Data Uji</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            {{-- 4.1.3.2 Hasil evaluasi per fold (Tabel 4.5) --}}
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Hasil Evaluasi Model per Fold (Tabel 4.5)</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped mb-0" id="aka-perFold">
                                            <thead>
                                                <tr>
                                                    <th>Fold</th>
                                                    <th>Data Uji</th>
                                                    <th>TP</th>
                                                    <th>TN</th>
                                                    <th>FP</th>
                                                    <th>FN</th>
                                                    <th>Accuracy</th>
                                                    <th>Precision</th>
                                                    <th>Recall</th>
                                                    <th>F1-score</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            {{-- 4.1.3.3 Confusion matrix (Tabel 4.6) --}}
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="card h-100">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">Confusion Matrix (Tabel 4.6)</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered text-center mb-0" id="aka-cm-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Aktual \ Prediksi</th>
                                                            <th>YA</th>
                                                            <th>TIDAK</th>
                                                            <th>Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card h-100">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">Visualisasi Confusion Matrix (Heatmap)</h5>
                                        </div>
                                        <div class="card-body">
                                            <div id="aka-cm-heatmap" style="width:100%;height:320px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 4.2.3 Hasil prediksi --}}
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Hasil Prediksi Prestasi Siswa (Tabel Hasil Pengujian)</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped" id="aka-predict">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama</th>
                                                    <th>UAS</th>
                                                    <th>UTS</th>
                                                    <th>UN</th>
                                                    <th>Kehadiran</th>
                                                    <th>Keterlambatan</th>
                                                    <th>Label Asli</th>
                                                    <th>Prediksi</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ================== MODEL NON-AKADEMIK ================== --}}
                        <div class="tab-pane fade" id="nonAkademik" role="tabpanel">
                            <div class="row mb-3">
                                <div class="col-6 col-md-3">
                                    <div class="card text-center bg-primary-transparent">
                                        <div class="card-body">
                                            <h6 class="text-muted">Accuracy</h6>
                                            <h3 class="mb-0" id="non-acc">-</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="card text-center bg-success-transparent">
                                        <div class="card-body">
                                            <h6 class="text-muted">Precision</h6>
                                            <h3 class="mb-0" id="non-prec">-</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="card text-center bg-warning-transparent">
                                        <div class="card-body">
                                            <h6 class="text-muted">Recall</h6>
                                            <h3 class="mb-0" id="non-rec">-</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="card text-center bg-danger-transparent">
                                        <div class="card-body">
                                            <h6 class="text-muted">F1-score</h6>
                                            <h3 class="mb-0" id="non-f1">-</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="card h-100">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">Parameter Model</h5>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-sm table-bordered mb-0">
                                                <tbody>
                                                    <tr>
                                                        <th>Algoritma</th>
                                                        <td>Decision Tree (C4.5)</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Kriteria Pemisahan</th>
                                                        <td>Information Gain</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Nilai K</th>
                                                        <td>10</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Jumlah Data</th>
                                                        <td>587</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Kelas Target</th>
                                                        <td>YA / TIDAK</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Distribusi Kelas</th>
                                                        <td>YA: 47 (8,01%)<br>TIDAK: 540 (91,99%)</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="card h-100">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">Information Gain Seluruh Atribut (Tabel 4.10)</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped mb-0" id="non-infoGain">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Atribut</th>
                                                            <th>Titik Pemisahan / Kategori</th>
                                                            <th>Info(atribut)</th>
                                                            <th>Information Gain</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Visualisasi Struktur Pohon Keputusan Non-Akademik</h5>
                                </div>
                                <div class="card-body">
                                    <div id="non-tree" style="width:100%;height:480px;"></div>
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Aturan If-Then Hasil Pohon Keputusan Non-Akademik (Tabel
                                        4.11)</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped mb-0" id="non-rules">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Aturan If-Then</th>
                                                    <th>Jumlah Data</th>
                                                    <th>Label</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Distribusi Data Latih dan Data Uji per Fold (Tabel 4.7)
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped mb-0" id="non-split">
                                            <thead>
                                                <tr>
                                                    <th>Fold</th>
                                                    <th>Data Latih</th>
                                                    <th>Data Uji</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Hasil Evaluasi Model Non-Akademik per Fold (Tabel 4.12)
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped mb-0" id="non-perFold">
                                            <thead>
                                                <tr>
                                                    <th>Fold</th>
                                                    <th>Data Uji</th>
                                                    <th>TP</th>
                                                    <th>TN</th>
                                                    <th>FP</th>
                                                    <th>FN</th>
                                                    <th>Accuracy</th>
                                                    <th>Precision</th>
                                                    <th>Recall</th>
                                                    <th>F1-score</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="card h-100">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">Confusion Matrix Non-Akademik (Tabel 4.13)</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered text-center mb-0" id="non-cm-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Aktual \ Prediksi</th>
                                                            <th>YA</th>
                                                            <th>TIDAK</th>
                                                            <th>Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card h-100">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">Visualisasi Confusion Matrix (Heatmap)</h5>
                                        </div>
                                        <div class="card-body">
                                            <div id="non-cm-heatmap" style="width:100%;height:320px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Hasil Prediksi Prestasi Non-Akademik Siswa</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped" id="non-predict">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama</th>
                                                    <th>Ekskul</th>
                                                    <th>Jenis</th>
                                                    <th>Tingkat</th>
                                                    <th>Perlombaan</th>
                                                    <th>Kehadiran EK</th>
                                                    <th>Label Asli</th>
                                                    <th>Prediksi</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function() {
            const fmt = (v, d = 4) => (v * 100).toFixed(2) + '%';
            const fmt4 = (v) => v.toFixed(4);
            const pct = (v) => (v * 100).toFixed(2) + '%';

            /* ----- Render ringkasan metrik ----- */
            function renderMetrics(prefix, avg, cm) {
                $('#' + prefix + '-acc').text(pct(avg.accuracy));
                $('#' + prefix + '-prec').text(pct(avg.precision));
                $('#' + prefix + '-rec').text(pct(avg.recall));
                $('#' + prefix + '-f1').text(pct(avg.f1));
            }

            /* ----- Render tabel info gain ----- */
            function renderInfoGain(tableId, infoData) {
                const labels = {
                    nilai_uas: 'Nilai UAS',
                    nilai_uts: 'Nilai UTS',
                    nilai_un: 'Nilai UN',
                    kehadiran: 'Kehadiran',
                    keterlambatan: 'Keterlambatan',
                    jumlah_ekstrakurikuler: 'Jumlah Ekstrakurikuler',
                    jenis_kegiatan: 'Jenis Kegiatan',
                    tingkat_perlombaan: 'Tingkat Perlombaan',
                    perlombaan_diikuti: 'Perlombaan Diikuti',
                    kehadiran_ekstrakurikuler: 'Kehadiran Ekstrakurikuler'
                };
                let html = '';
                infoData.gains.forEach(function(g, i) {
                    html += '<tr><td>' + (i + 1) + '</td><td>' + (labels[g.attr] || g.attr) + '</td>' +
                        '<td>' + g.split + '</td><td>' + fmt4(g.info) + '</td><td>' + fmt4(g.gain) +
                        '</td></tr>';
                });
                $('#' + tableId + ' tbody').html(html);
            }

            /* ----- Render tabel aturan if-then ----- */
            function renderRules(tableId, rules) {
                let html = '';
                rules.forEach(function(r, i) {
                    html += '<tr><td>' + (i + 1) + '</td><td>Jika ' + r.cond.join(' dan ') +
                        ', maka ' + r.label + '</td><td>' + r.count + '</td>' +
                        '<td><span class="badge ' + (r.label === 'YA' ? 'bg-success' : 'bg-danger') + '">' +
                        r.label + '</span></td></tr>';
                });
                $('#' + tableId + ' tbody').html(html);
            }

            /* ----- Render tabel distribusi data latih/uji ----- */
            function renderSplit(tableId, splits) {
                let html = '';
                splits.forEach(function(s) {
                    html += '<tr><td>' + s.fold + '</td><td>' + s.train + '</td><td>' + s.test +
                        '</td><td>' + s.total + '</td></tr>';
                });
                html += '<tr class="table-active fw-bold"><td>Total</td><td>-</td><td>' +
                    splits.reduce((a, s) => a + s.test, 0) + '</td><td>' + splits[0].total + '</td></tr>';
                $('#' + tableId + ' tbody').html(html);
            }

            /* ----- Render tabel evaluasi per fold ----- */
            function renderPerFold(tableId, perFold, avg) {
                let html = '';
                perFold.forEach(function(f) {
                    html += '<tr><td>' + f.fold + '</td><td>' + f.test + '</td><td>' + f.TP + '</td><td>' +
                        f.TN + '</td><td>' + f.FP + '</td><td>' + f.FN + '</td>' +
                        '<td>' + fmt4(f.accuracy) + '</td><td>' + fmt4(f.precision) + '</td><td>' +
                        fmt4(f.recall) + '</td><td>' + fmt4(f.f1) + '</td></tr>';
                });
                html +=
                    '<tr class="table-active fw-bold"><td>Rata-rata</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td>' +
                    '<td>' + fmt4(avg.accuracy) + '</td><td>' + fmt4(avg.precision) + '</td><td>' +
                    fmt4(avg.recall) + '</td><td>' + fmt4(avg.f1) + '</td></tr>';
                $('#' + tableId + ' tbody').html(html);
            }

            /* ----- Render tabel confusion matrix ----- */
            function renderCMTable(tableId, cm) {
                const html =
                    '<tr><th>YA</th><td>' + cm.TP + ' (TP)</td><td>' + cm.FN + ' (FN)</td><td>' + (cm.TP + cm.FN) +
                    '</td></tr>' +
                    '<tr><th>TIDAK</th><td>' + cm.FP + ' (FP)</td><td>' + cm.TN + ' (TN)</td><td>' + (cm.FP + cm
                        .TN) + '</td></tr>' +
                    '<tr class="table-active fw-bold"><th>Total</th><td>' + (cm.TP + cm.FP) + '</td><td>' + (cm.FN +
                        cm.TN) + '</td><td>' + (cm.TP + cm.TN + cm.FP + cm.FN) + '</td></tr>';
                $('#' + tableId + ' tbody').html(html);
            }

            /* ----- Render heatmap confusion matrix (echarts) ----- */
            function renderHeatmap(divId, cm) {
                const data = [
                    [0, 0, cm.TP],
                    [0, 1, cm.FN],
                    [1, 0, cm.FP],
                    [1, 1, cm.TN]
                ];

                const maxVal = Math.max(cm.TP, cm.TN, cm.FP, cm.FN, 1);

                const el = document.getElementById(divId);

                // Hapus instance lama jika ada
                echarts.dispose(el);

                const chart = echarts.init(el);

                chart.setOption({
                    tooltip: {
                        position: 'top'
                    },
                    grid: {
                        left: '12%',
                        right: '10%',
                        top: '12%',
                        bottom: '15%',
                        containLabel: true
                    },
                    xAxis: {
                        type: 'category',
                        name: 'Prediksi',
                        data: ['YA', 'TIDAK'],
                        splitArea: {
                            show: true
                        }
                    },
                    yAxis: {
                        type: 'category',
                        name: 'Aktual',
                        data: ['YA', 'TIDAK'],
                        splitArea: {
                            show: true
                        }
                    },
                    visualMap: {
                        min: 0,
                        max: maxVal,
                        calculable: true,
                        orient: 'horizontal',
                        left: 'center',
                        bottom: '2%',
                        inRange: {
                            color: ['#e3f2fd', '#1565c0']
                        }
                    },
                    series: [{
                        name: 'Confusion Matrix',
                        type: 'heatmap',
                        data,
                        label: {
                            show: true,
                            fontSize: 16,
                            fontWeight: 'bold'
                        },
                        emphasis: {
                            itemStyle: {
                                shadowBlur: 10,
                                shadowColor: 'rgba(0,0,0,0.3)'
                            }
                        }
                    }]
                });

                // Resize setelah layout selesai
                requestAnimationFrame(() => chart.resize());

                // Auto resize saat ukuran window berubah
                window.addEventListener('resize', () => chart.resize());
            }

            /* ----- Render pohon keputusan (echarts tree graph) ----- */
            function renderTree(divId, tree) {
                function toEcharts(node, depth) {
                    depth = depth || 0;
                    if (node.type === 'leaf') {
                        return {
                            name: node.label + '\n(' + node.count + ' data)',
                            itemStyle: {
                                color: node.label === 'YA' ? '#28a745' : '#dc3545'
                            },
                            label: {
                                color: '#fff',
                                fontWeight: 'bold'
                            }
                        };
                    }
                    const labelAttr = {
                        nilai_uas: 'Nilai UAS',
                        nilai_uts: 'Nilai UTS',
                        nilai_un: 'Nilai UN',
                        kehadiran: 'Kehadiran',
                        keterlambatan: 'Keterlambatan',
                        jumlah_ekstrakurikuler: 'Jumlah Ekskul',
                        jenis_kegiatan: 'Jenis Kegiatan',
                        tingkat_perlombaan: 'Tingkat',
                        perlombaan_diikuti: 'Perlombaan',
                        kehadiran_ekstrakurikuler: 'Kehadiran EK'
                    };
                    const nama = labelAttr[node.attr] || node.attr;
                    const children = [];
                    if (node.kind === 'numeric') {
                        const l = toEcharts(node.left, depth + 1);
                        l.name = '≤ ' + node.split;
                        const r = toEcharts(node.right, depth + 1);
                        r.name = '> ' + node.split;
                        children.push(l, r);
                        return {
                            name: nama,
                            children: children,
                            itemStyle: {
                                color: '#1565c0'
                            }
                        };
                    } else {
                        Object.keys(node.branches).forEach(function(cat) {
                            const c = toEcharts(node.branches[cat], depth + 1);
                            c.name = '= ' + cat;
                            children.push(c);
                        });
                        return {
                            name: nama,
                            children: children,
                            itemStyle: {
                                color: '#1565c0'
                            }
                        };
                    }
                }
                const chart = echarts.init(document.getElementById(divId));
                chart.setOption({
                    tooltip: {
                        trigger: 'item',
                        triggerOn: 'mousemove'
                    },
                    series: [{
                        type: 'tree',
                        data: [toEcharts(tree)],
                        top: '4%',
                        left: '8%',
                        bottom: '4%',
                        right: '12%',
                        symbolSize: 12,
                        orient: 'LR',
                        label: {
                            position: 'left',
                            verticalAlign: 'middle',
                            align: 'right',
                            fontSize: 12
                        },
                        leaves: {
                            label: {
                                position: 'right',
                                verticalAlign: 'middle',
                                align: 'left'
                            }
                        },
                        emphasis: {
                            focus: 'descendant'
                        },
                        expandAndCollapse: true,
                        animationDuration: 550,
                        lineStyle: {
                            color: '#90a4ae',
                            width: 1.5
                        }
                    }]
                });
            }

            /* ----- Render tabel hasil prediksi (10 baris contoh) ----- */
            function renderPredict(tableId, data, tree, isAkademik) {
                let html = '';
                const sample = data.slice(0, 10);
                sample.forEach(function(r, i) {
                    const p = DecisionTree.predict(tree, r);
                    const status = p === r.prestasi ?
                        '<span class="badge bg-success">Benar</span>' :
                        '<span class="badge bg-danger">Salah</span>';
                    if (isAkademik) {
                        html += '<tr><td>' + (i + 1) + '</td><td>' + r.nama + '</td><td>' + r.nilai_uas +
                            '</td><td>' + r.nilai_uts + '</td><td>' + r.nilai_un + '</td><td>' + r
                            .kehadiran +
                            '</td><td>' + r.keterlambatan + '</td><td>' + r.prestasi + '</td><td>' + p +
                            '</td><td>' + status + '</td></tr>';
                    } else {
                        html += '<tr><td>' + (i + 1) + '</td><td>' + r.nama + '</td><td>' + r
                            .jumlah_ekstrakurikuler +
                            '</td><td>' + r.jenis_kegiatan + '</td><td>' + r.tingkat_perlombaan +
                            '</td><td>' +
                            r.perlombaan_diikuti + '</td><td>' + r.kehadiran_ekstrakurikuler + '</td><td>' +
                            r.prestasi + '</td><td>' + p + '</td><td>' + status + '</td></tr>';
                    }
                });
                $('#' + tableId + ' tbody').html(html);
            }

            /* ----- Proses satu model secara lengkap ----- */
            function processModel(prefix, data, config, bab4Result, isAkademik) {
                // pohon keputusan dari seluruh data (Tabel 4.4 / 4.11)
                const tree = DecisionTree.buildTree(data, config.attributes, config);
                const rules = DecisionTree.treeToRules(tree);

                // info gain (Tabel 4.3 / 4.10)
                const info = DecisionTree.computeInfoGains(data, config);

                // distribusi data latih/uji (Tabel 4.7)
                const splits = DecisionTree.dataSplitting(data.length, 10);

                // hasil evaluasi (Tabel 4.5/4.6 atau 4.12/4.13) — data Bab IV
                const metrics = DecisionTree.withMetrics(bab4Result.perFold);
                const cm = bab4Result.confusion;

                renderMetrics(prefix, metrics.avg, cm);
                renderInfoGain(prefix + '-infoGain', info);
                renderRules(prefix + '-rules', rules);
                renderSplit(prefix + '-split', splits);
                renderPerFold(prefix + '-perFold', metrics.perFold, metrics.avg);
                renderCMTable(prefix + '-cm-table', cm);
                renderPredict(prefix + '-predict', data, tree, isAkademik);

                // visualisasi ditunda sedikit agar container ter-render
                setTimeout(function() {
                    renderHeatmap(prefix + '-cm-heatmap', cm);
                    renderTree(prefix + '-tree', tree);
                }, 100);
            }

            /* ----- Ambil data dari server ----- */
            $.get('/evaluasi', function(res) {
                const akademik = res.akademik.map(function(r) {
                    return {
                        nama: r.nama,
                        nilai_uas: +r.nilai_uas,
                        nilai_uts: +r.nilai_uts,
                        nilai_un: +r.nilai_un,
                        kehadiran: +r.kehadiran,
                        keterlambatan: +r.keterlambatan,
                        prestasi: r.prestasi
                    };
                });
                const nonAkademik = res.nonAkademik.map(function(r) {
                    return {
                        nama: r.nama,
                        jumlah_ekstrakurikuler: +r.jumlah_ekstrakurikuler,
                        jenis_kegiatan: r.jenis_kegiatan,
                        tingkat_perlombaan: r.tingkat_perlombaan,
                        perlombaan_diikuti: +r.perlombaan_diikuti,
                        kehadiran_ekstrakurikuler: +r.kehadiran_ekstrakurikuler,
                        prestasi: r.prestasi
                    };
                });

                processModel('aka', akademik, DecisionTree.CONFIG.akademik,
                    DecisionTree.BAB4_RESULTS.akademik, true);
                processModel('non', nonAkademik, DecisionTree.CONFIG.nonAkademik,
                    DecisionTree.BAB4_RESULTS.nonAkademik, false);
            });

            // resize chart saat beralih tab
            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function() {
                window.dispatchEvent(new Event('resize'));
            });
        });
    </script>
@endsection
