/* =========================================================================
 * decision-tree.js
 * Implementasi algoritma Decision Tree C4.5 + K-Fold Cross Validation (K=10)
 * untuk prediksi prestasi akademik & non-akademik siswa.
 *
 * Sesuai Bab IV skripsi: algoritma C4.5 (entropy, information gain, split
 * point optimal) dengan validasi 10-Fold Cross Validation sekuensial
 * (7 fold x 59 + 3 fold x 58 = 587 catatan).
 *
 * Seluruh metrik evaluasi (accuracy, precision, recall, F1-score, serta
 * confusion matrix) DIHITUNG LANGSUNG dari data di basis data pada setiap
 * fold — tanpa nilai yang di-hardcode.
 * ========================================================================= */

(function (global) {
    'use strict';

    /* -------------------- Util matematis -------------------- */
    function log2(x) {
        return Math.log(x) / Math.LN2;
    }

    function entropy(rows) {
        const n = rows.length;
        if (n === 0) return 0;
        const counts = {};
        rows.forEach(function (r) {
            counts[r.prestasi] = (counts[r.prestasi] || 0) + 1;
        });
        let e = 0;
        Object.keys(counts).forEach(function (k) {
            const p = counts[k] / n;
            if (p > 0) e -= p * log2(p);
        });
        return e;
    }

    /* ----- Split numerik: cari titik tengah optimal (midpoint) -----
     * Kandidat pemisahan ditolak bila salah satu cabang berisi kurang dari
     * minLeaf data (pre-pruning: minimum samples per leaf).
     */
    function bestNumericSplit(rows, attr, minLeaf) {
        minLeaf = minLeaf || 1;
        const vals = Array.from(new Set(rows.map(function (r) {
            return Number(r[attr]);
        }))).sort(function (a, b) {
            return a - b;
        });
        let bestGain = -Infinity,
            bestSplit = null,
            bestInfo = null;
        const baseEntropy = entropy(rows);
        for (let i = 0; i < vals.length - 1; i++) {
            const mid = (vals[i] + vals[i + 1]) / 2;
            const left = rows.filter(function (r) {
                return Number(r[attr]) <= mid;
            });
            const right = rows.filter(function (r) {
                return Number(r[attr]) > mid;
            });
            if (left.length < minLeaf || right.length < minLeaf) continue;
            const info = (left.length / rows.length) * entropy(left) +
                (right.length / rows.length) * entropy(right);
            const gain = baseEntropy - info;
            if (gain > bestGain) {
                bestGain = gain;
                bestSplit = mid;
                bestInfo = info;
            }
        }
        return {
            split: bestSplit,
            gain: bestGain,
            info: bestInfo
        };
    }

    /* ----- Split kategorikal (multiway) ----- */
    function categoricalSplit(rows, attr) {
        const cats = Array.from(new Set(rows.map(function (r) {
            return r[attr];
        })));
        let info = 0;
        cats.forEach(function (cat) {
            const subset = rows.filter(function (r) {
                return r[attr] === cat;
            });
            info += (subset.length / rows.length) * entropy(subset);
        });
        return {
            gain: entropy(rows) - info,
            info: info,
            categories: cats
        };
    }

    /* ----- Hitung information gain SELURUH atribut (untuk Tabel 4.3/4.10) ----- */
    function computeInfoGains(rows, config) {
        const baseEntropy = entropy(rows);
        const results = [];
        config.attributes.forEach(function (attr) {
            let r;
            if (config.numeric.indexOf(attr) !== -1) {
                r = bestNumericSplit(rows, attr, config.minSamplesLeaf || 1);
                results.push({
                    attr: attr,
                    split: r.split !== null ? ('≤ ' + r.split) : '-',
                    info: r.info,
                    gain: r.gain
                });
            } else {
                r = categoricalSplit(rows, attr);
                results.push({
                    attr: attr,
                    split: 'Multiway (' + r.categories.length + ' kategori)',
                    info: r.info,
                    gain: r.gain
                });
            }
        });
        results.sort(function (a, b) {
            return b.gain - a.gain;
        });
        return {
            entropy: baseEntropy,
            gains: results
        };
    }

    /* ----- Bangun pohon keputusan C4.5 secara rekursif ----- */
    function buildTree(rows, attributes, config) {
        const labels = rows.map(function (r) {
            return r.prestasi;
        });
        const uniq = Array.from(new Set(labels));

        // node murni
        if (uniq.length === 1) {
            return {
                type: 'leaf',
                label: uniq[0],
                count: rows.length
            };
        }
        // tidak ada atribut tersisa / data terlalu sedikit
        if (attributes.length === 0 || rows.length < 2) {
            const maj = labels.filter(function (l) {
                return l === uniq[0];
            }).length >= labels.length / 2 ? uniq[0] : uniq[1];
            return {
                type: 'leaf',
                label: maj,
                count: rows.length
            };
        }

        let best = null;
        attributes.forEach(function (attr) {
            let r;
            if (config.numeric.indexOf(attr) !== -1) {
                r = bestNumericSplit(rows, attr, config.minSamplesLeaf || 1);
                if (r.split !== null && (!best || r.gain > best.gain)) {
                    best = {
                        attr: attr,
                        kind: 'numeric',
                        split: r.split,
                        gain: r.gain
                    };
                }
            } else {
                r = categoricalSplit(rows, attr);
                if (!best || r.gain > best.gain) {
                    best = {
                        attr: attr,
                        kind: 'categorical',
                        categories: r.categories,
                        gain: r.gain
                    };
                }
            }
        });

        // tidak ada pemisahan yang menurunkan entropy
        if (!best || best.gain <= 0) {
            const maj = labels.filter(function (l) {
                return l === uniq[0];
            }).length >= labels.length / 2 ? uniq[0] : uniq[1];
            return {
                type: 'leaf',
                label: maj,
                count: rows.length
            };
        }

        const node = {
            type: 'node',
            attr: best.attr,
            kind: best.kind,
            count: rows.length
        };
        if (best.kind === 'numeric') {
            node.split = best.split;
            node.left = buildTree(rows.filter(function (r) {
                return Number(r[best.attr]) <= best.split;
            }), attributes, config);
            node.right = buildTree(rows.filter(function (r) {
                return Number(r[best.attr]) > best.split;
            }), attributes, config);
        } else {
            node.branches = {};
            best.categories.forEach(function (cat) {
                node.branches[cat] = buildTree(rows.filter(function (r) {
                    return r[best.attr] === cat;
                }), attributes.filter(function (a) {
                    return a !== best.attr;
                }), config);
            });
        }
        return node;
    }

    /* ----- Prediksi satu baris dengan pohon ----- */
    function predict(tree, row) {
        let n = tree;
        while (n.type === 'node') {
            if (n.kind === 'numeric') {
                n = Number(row[n.attr]) <= n.split ? n.left : n.right;
            } else {
                const v = row[n.attr];
                n = n.branches[v] || n.branches['__default__'] || Object.values(n.branches)[0];
            }
        }
        return n.label;
    }

    /* ----- Ekstrak aturan if-then dari pohon ----- */
    function treeToRules(tree, path) {
        path = path || [];
        if (tree.type === 'leaf') {
            return [{
                cond: path.slice(),
                label: tree.label,
                count: tree.count
            }];
        }
        const rules = [];
        if (tree.kind === 'numeric') {
            rules.push.apply(rules, treeToRules(tree.left, path.concat(tree.attr + ' ≤ ' + tree.split)));
            rules.push.apply(rules, treeToRules(tree.right, path.concat(tree.attr + ' > ' + tree.split)));
        } else {
            Object.keys(tree.branches).forEach(function (cat) {
                rules.push.apply(rules, treeToRules(tree.branches[cat], path.concat(tree.attr + ' = ' + cat)));
            });
        }
        return rules;
    }

    /* ----- Ukuran fold sekuensial (7x59 + 3x58 = 587) ----- */
    function foldSizes(n, k) {
        const base = Math.floor(n / k);
        const sizes = new Array(k).fill(base);
        for (let i = 0; i < (n - base * k); i++) sizes[i]++;
        return sizes;
    }

    /* ----- Distribusi data latih/uji per fold (Tabel 4.7) ----- */
    function dataSplitting(n, k) {
        const sizes = foldSizes(n, k);
        const folds = [];
        sizes.forEach(function (sz, i) {
            folds.push({
                fold: i + 1,
                train: n - sz,
                test: sz,
                total: n
            });
        });
        return folds;
    }

    /* ----- K-Fold Cross Validation (K=10) dengan C4.5 -----
     * Membangun pohon C4.5 pada data latih tiap fold dan menguji pada data uji.
     * Mengembalikan TP/TN/FP/FN serta metrik per fold + akumulasi.
     */
    function kFoldCrossValidation(data, config, k) {
        k = k || 10;
        const sizes = foldSizes(data.length, k);
        const folds = [];
        let start = 0;
        sizes.forEach(function (sz) {
            folds.push(data.slice(start, start + sz));
            start += sz;
        });

        const perFold = [];
        let TP = 0,
            TN = 0,
            FP = 0,
            FN = 0;
        for (let i = 0; i < k; i++) {
            const test = folds[i];
            const train = data.filter(function (_, idx) {
                let s = 0;
                for (let j = 0; j < i; j++) s += sizes[j];
                return idx < s || idx >= s + sizes[i];
            });
            const tree = buildTree(train, config.attributes, config);
            let tp = 0,
                tn = 0,
                fp = 0,
                fn = 0;
            test.forEach(function (r) {
                const p = predict(tree, r);
                if (r.prestasi === 'YA' && p === 'YA') tp++;
                else if (r.prestasi === 'TIDAK' && p === 'TIDAK') tn++;
                else if (r.prestasi === 'TIDAK' && p === 'YA') fp++;
                else fn++;
            });
            TP += tp;
            TN += tn;
            FP += fp;
            FN += fn;
            const acc = (tp + tn) / test.length;
            // Metrik yang tidak terdefinisi (pembagi 0) dianggap 0,
            // sesuai konvensi umum (zero_division = 0) — bukan dipaksakan 1.
            const prec = tp + fp > 0 ? tp / (tp + fp) : 0;
            const rec = tp + fn > 0 ? tp / (tp + fn) : 0;
            const f1 = prec + rec > 0 ? 2 * prec * rec / (prec + rec) : 0;
            perFold.push({
                fold: i + 1,
                test: test.length,
                TP: tp,
                TN: tn,
                FP: fp,
                FN: fn,
                accuracy: acc,
                precision: prec,
                recall: rec,
                f1: f1
            });
        }

        // rata-rata per fold
        const avg = perFold.reduce(function (a, x) {
            return {
                accuracy: a.accuracy + x.accuracy,
                precision: a.precision + x.precision,
                recall: a.recall + x.recall,
                f1: a.f1 + x.f1
            };
        }, {
            accuracy: 0,
            precision: 0,
            recall: 0,
            f1: 0
        });
        Object.keys(avg).forEach(function (k2) {
            avg[k2] = avg[k2] / k;
        });

        return {
            perFold: perFold,
            avg: avg,
            confusion: {
                TP: TP,
                TN: TN,
                FP: FP,
                FN: FN
            }
        };
    }

    /* ----- Konfigurasi atribut tiap dataset (sesuai Bab IV) -----
     * minSamplesLeaf: jumlah minimum data pada setiap daun hasil pemisahan
     * (pre-pruning) — kondisi berhenti "jumlah data mencapai batas minimum
     * yang telah ditentukan" sebagaimana dijelaskan pada Bab IV.
     */
    const CONFIG = {
        akademik: {
            attributes: ['nilai_uas', 'nilai_uts', 'nilai_un', 'kehadiran', 'keterlambatan'],
            numeric: ['nilai_uas', 'nilai_uts', 'nilai_un', 'kehadiran', 'keterlambatan'],
            labelField: 'prestasi',
            minSamplesLeaf: 15
        },
        nonAkademik: {
            attributes: ['jumlah_ekstrakurikuler', 'jenis_kegiatan', 'tingkat_perlombaan',
                'perlombaan_diikuti', 'kehadiran_ekstrakurikuler'
            ],
            numeric: ['jumlah_ekstrakurikuler', 'perlombaan_diikuti', 'kehadiran_ekstrakurikuler'],
            labelField: 'prestasi',
            minSamplesLeaf: 15
        }
    };

    /* ----- Label tampilan untuk nama atribut ----- */
    const ATTR_LABELS = {
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

    global.DecisionTree = {
        entropy: entropy,
        computeInfoGains: computeInfoGains,
        buildTree: buildTree,
        predict: predict,
        treeToRules: treeToRules,
        kFoldCrossValidation: kFoldCrossValidation,
        dataSplitting: dataSplitting,
        foldSizes: foldSizes,
        CONFIG: CONFIG,
        ATTR_LABELS: ATTR_LABELS
    };
})(window);
