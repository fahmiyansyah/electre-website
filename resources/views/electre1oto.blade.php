<!DOCTYPE html>
<html>
<head>
    <title>Perhitungan ELECTRE I</title>
</head>
<body>
    <h1>Perhitungan ELECTRE I</h1>

    <!-- Tabel 5. Calon Penerima Beasiswa -->
    <table id="calonBeasiswa" border="1">
        <tr>
            <th>Calon Penerima Beasiswa</th>
            <th>K1</th>
            <th>K2</th>
            <th>K3</th>
            <th>K4</th>
        </tr>
        <tr>
            <td>Mahadi</td>
            <td>5</td>
            <td>3</td>
            <td>3</td>
            <td>3</td>
        </tr>
        <tr>
            <td>Saimon</td>
            <td>4</td>
            <td>4</td>
            <td>3</td>
            <td>4</td>
        </tr>
        <tr>
            <td>Wahdi</td>
            <td>4</td>
            <td>4</td>
            <td>3</td>
            <td>3</td>
        </tr>
        <tr>
            <td>Aditya</td>
            <td>2</td>
            <td>4</td>
            <td>3</td>
            <td>3</td>
        </tr>
    </table>

    <!-- Tabel 6. Bobot Pengambilan Siswa -->
    <table id="bobotKriteria" border="1">
        <tr>
            <th>Kriteria</th>
            <th>Bobot</th>
        </tr>
        <tr>
            <td>K1</td>
            <td>5</td>
        </tr>
        <tr>
            <td>K2</td>
            <td>2</td>
        </tr>
        <tr>
            <td>K3</td>
            <td>4</td>
        </tr>
        <tr>
            <td>K4</td>
            <td>2</td>
        </tr>
    </table>

    <button id="hitungButton">Hitung Hasil</button>

    <div id="hasilPerhitungan">
        <!-- Hasil perhitungan akan ditampilkan di sini -->
    </div>

    <script>
        document.getElementById("hitungButton").addEventListener("click", function () {
            // Mendapatkan data dari tabel HTML
            const calonBeasiswa = document.getElementById("calonBeasiswa");
            const bobotKriteria = document.getElementById("bobotKriteria");
            const hasilPerhitungan = document.getElementById("hasilPerhitungan");

            // Fungsi untuk menghitung matrix decision
            function hitungMatrixDecision() {
                const matrixDecision = [];
                for (let i = 1; i < calonBeasiswa.rows.length; i++) {
                    const row = calonBeasiswa.rows[i];
                    const data = [];
                    for (let j = 1; j < row.cells.length; j++) {
                        data.push(parseInt(row.cells[j].textContent));
                    }
                    matrixDecision.push(data);
                }
                return matrixDecision;
            }

            // Fungsi untuk menghitung matrix normalisasi
            function hitungMatrixNormalisasi(matrixDecision) {
                const matrixNormalisasi = [];
                const bobotKriteria = hitungBobotKriteria();
                for (let i = 0; i < matrixDecision.length; i++) {
                    const row = matrixDecision[i];
                    const normalisasi = [];
                    for (let j = 0; j < row.length; j++) {
                        normalisasi.push(row[j] / bobotKriteria[j]);
                    }
                    matrixNormalisasi.push(normalisasi);
                }
                return matrixNormalisasi;
            }

            // Fungsi untuk menghitung bobot kriteria
            function hitungBobotKriteria() {
                const bobotKriteria = [];
                for (let i = 1; i < bobotKriteria.rows.length; i++) {
                    const row = bobotKriteria.rows[i];
                    const bobot = parseInt(row.cells[1].textContent);
                    bobotKriteria.push(bobot);
                }
                return bobotKriteria;
            }

            // Fungsi untuk menghitung concordance
            function hitungConcordance(matrixNormalisasi) {
                const concordance = [];
                for (let i = 0; i < matrixNormalisasi.length; i++) {
                    const row = matrixNormalisasi[i];
                    const concordanceRow = [];
                    for (let j = 0; j < matrixNormalisasi.length; j++) {
                        if (i === j) {
                            concordanceRow.push(0);
                        } else {
                            let count = 0;
                            for (let k = 0; k < row.length; k++) {
                                if (row[k] >= matrixNormalisasi[j][k]) {
                                    count++;
                                }
                            }
                            concordanceRow.push(count);
                        }
                    }
                    concordance.push(concordanceRow);
                }
                return concordance;
            }

            // Fungsi untuk menghitung disconcordance
            function hitungDisconcordance(matrixNormalisasi) {
                const disconcordance = [];
                for (let i = 0; i < matrixNormalisasi.length; i++) {
                    const row = matrixNormalisasi[i];
                    const disconcordanceRow = [];
                    for (let j = 0; j < matrixNormalisasi.length; j++) {
                        if (i === j) {
                            disconcordanceRow.push(0);
                        } else {
                            let maxDiff = 0;
                            for (let k = 0; k < row.length; k++) {
                                const diff = matrixNormalisasi[i][k] - matrixNormalisasi[j][k];
                                if (diff > maxDiff) {
                                    maxDiff = diff;
                                }
                            }
                            disconcordanceRow.push(maxDiff);
                        }
                    }
                    disconcordance.push(disconcordanceRow);
                }
                return disconcordance;
            }

            // Fungsi untuk menghitung matrix disconcordance
            function hitungMatrixDisconcordance(disconcordance, threshold) {
                const matrixDisconcordance = [];
                for (let i = 0; i < disconcordance.length; i++) {
                    const row = disconcordance[i];
                    const disconcordanceRow = [];
                    for (let j = 0; j < row.length; j++) {
                        if (row[j] <= threshold) {
                            disconcordanceRow.push(1);
                        } else {
                            disconcordanceRow.push(0);
                        }
                    }
                    matrixDisconcordance.push(disconcordanceRow);
                }
                return matrixDisconcordance;
            }

            // Fungsi untuk menghitung matrix dominan keseluruhan
            function hitungMatrixDominanKeseluruhan(matrixConcordance, matrixDisconcordance) {
                const matrixDominanKeseluruhan = [];
                for (let i = 0; i < matrixConcordance.length; i++) {
                    const rowConcordance = matrixConcordance[i];
                    const rowDisconcordance = matrixDisconcordance[i];
                    const dominanRow = [];
                    for (let j = 0; j < rowConcordance.length; j++) {
                        if (rowConcordance[j] === 1 && rowDisconcordance[j] === 0) {
                            dominanRow.push(1);
                        } else {
                            dominanRow.push(0);
                        }
                    }
                    matrixDominanKeseluruhan.push(dominanRow);
                }
                return matrixDominanKeseluruhan;
            }

            // Fungsi untuk menghitung ranking
            function hitungRanking(matrixDominanKeseluruhan) {
                const ranking = [];
                for (let i = 0; i < matrixDominanKeseluruhan.length; i++) {
                    const row = matrixDominanKeseluruhan[i];
                    const sum = row.reduce((acc, value) => acc + value, 0);
                    ranking.push({
                        calon: `Calon ${i + 1}`,
                        dominan: sum
                    });
                }
                // Urutkan berdasarkan dominan dalam urutan menurun
                ranking.sort((a, b) => b.dominan - a.dominan);
                return ranking;
            }

            // Hitung matriks Decision
            const matrixDecision = hitungMatrixDecision();
            console.log("Matrix Decision:", matrixDecision);

            // Hitung matriks Normalisasi
            const matrixNormalisasi = hitungMatrixNormalisasi(matrixDecision);
            console.log("Matrix Normalisasi:", matrixNormalisasi);

            // Hitung matriks Concordance
            const concordance = hitungConcordance(matrixNormalisasi);
            console.log("Concordance:", concordance);

            // Hitung matriks Disconcordance
            const disconcordance = hitungDisconcordance(matrixNormalisasi);
            console.log("Disconcordance:", disconcordance);

            // Set threshold untuk matriks Disconcordance
            const disconcordanceThreshold = 2;

            // Hitung matriks Disconcordance dengan threshold
            const matrixDisconcordance = hitungMatrixDisconcordance(disconcordance, disconcordanceThreshold);
            console.log("Matrix Disconcordance:", matrixDisconcordance);

            // Hitung matriks Dominan Keseluruhan
            const matrixDominanKeseluruhan = hitungMatrixDominanKeseluruhan(concordance, matrixDisconcordance);
            console.log("Matrix Dominan Keseluruhan:", matrixDominanKeseluruhan);

            // Hitung Ranking
            const ranking = hitungRanking(matrixDominanKeseluruhan);
            console.log("Ranking:", ranking);

            // Tampilkan hasil perhitungan di HTML
            hasilPerhitungan.innerHTML = JSON.stringify(ranking, null, 2);
        });
    </script>
</body>
</html>
