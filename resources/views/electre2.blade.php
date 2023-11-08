<!DOCTYPE html>
<html>
<head>
    <title>Decision Support System</title>
    <style>
        /* CSS untuk hiasan dan warna biru muda */
        body {
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
        }

        h1 {
            text-align: center; /* Teks judul di tengah halaman */
            color: #007acc; /* Warna biru muda */
        }

        h2 {
            color: #007acc; /* Warna biru muda */
        }

        button {
            background-color: #007acc; /* Warna biru muda */
            color: #fff;
            border: none;
            padding: 10px 20px;
            margin: 10px;
            cursor: pointer;
        }

        button:hover {
            background-color: #005b99; /* Warna biru yang sedikit lebih gelap saat hover */
        }

        table {
            border-collapse: collapse;
            width: 100%;
            max-width: 800px;
            margin: 10px;
        }

        table, th, td {
            border: 1px solid #007acc; /* Warna garis tepi biru muda */
        }

        th, td {
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #007acc; /* Warna latar belakang header biru muda */
            color: #fff;
        }

        form {
            margin: 10px;
        }

        label {
            font-weight: bold;
        }

        input {
            padding: 5px;
            margin: 5px;
        }

    </style>
</head>
<body>
    <h1>Decision Support System</h1>
    <button id="addAlternative">Tambah Alternatif</button>
    <button id="addCriterion">Tambah Kriteria</button>

    <div class="alternatives-section">
        <!-- Alternatif akan ditambahkan di sini -->
    </div>

    <div class="criteria-section">
        <!-- Kriteria akan ditambahkan di sini -->
    </div>

    <h2>Matriks Keputusan</h2>
    <table class="decision-matrix">
        <!-- Matriks Keputusan akan ditampilkan di sini -->
    </table>

    <h2>Hasil Perhitungan Electre 2</h2>
    <table id="electre2-results">
        <thead>
            <tr>
                <th>Alternatif</th>
                <th>Nilai Electre 2</th>
            </tr>
        </thead>
        <tbody id="electre2-results-body">
            <!-- Hasil perhitungan Electre 2 akan ditampilkan di sini -->
        </tbody>
    </table>

    <form>
        <label for="q">Nilai q:</label>
        <input type="number" name="q" id="q" class="form-control" step="0.01">
        <label for="p">Nilai p:</label>
        <input type="number" name="p" id="p" class="form-control" step="0.01">
        <label for="v">Nilai v:</label>
        <input type="number" name="v" id="v" class="form-control" step="0.01">
        <button type="submit">Hitung Electre 2</button>
    </form>

    <script>
        let alternativeNumber = 1;
        let criterionNumber = 1;

        document.getElementById('addAlternative').addEventListener('click', function() {
            const alternativesSection = document.querySelector('.alternatives-section');
            const newAlternative = document.createElement('div');
            newAlternative.innerHTML = `
                <label for="alternative${alternativeNumber}">Alternatif ${alternativeNumber}:</label>
                <input type="text" name="alternatives[${alternativeNumber}]" class="form-control alternative">
            `;
            alternativesSection.appendChild(newAlternative);
            alternativeNumber++;
        });

        document.getElementById('addCriterion').addEventListener('click', function() {
            const criteriaSection = document.querySelector('.criteria-section');
            const newCriterion = document.createElement('div');
            newCriterion.innerHTML = `
                <label for="criterion${criterionNumber}">Nama Kriteria ${criterionNumber}:</label>
                <input type="text" name="criteria[${criterionNumber}]" class="form-control criterion">
                <label for="weight${criterionNumber}">Weight Kriteria ${criterionNumber}:</label>
                <input type="number" name="weights[${criterionNumber}]" class="form-control weight" step="0.01">
            `;
            criteriaSection.appendChild(newCriterion);
            criterionNumber++;
        });

        // Function to create rows for decision matrix values
        function createDecisionMatrixRows(alternativesCount, criteriaCount) {
            const decisionMatrix = document.querySelector('.decision-matrix');
            decisionMatrix.innerHTML = '';

            // Create header row with labels for each criterion
            let headerRow = '<tr><th>Choice</th>';
            for (let j = 1; j <= criteriaCount; j++) {
                headerRow += `<th>Kriteria ${j}</th>`;
            }
            headerRow += '</tr>';
            decisionMatrix.innerHTML += headerRow;

            // Create rows for entering decision matrix values
            for (let i = 1; i <= alternativesCount; i++) {
                let row = `<tr><td>Alternatif ${i}</td>`;
                for (let j = 1; j <= criteriaCount; j++) {
                    row += `
                        <td>
                            <input type="number" name="decision_matrix[${i}][criterion_${j}]" class="form-control">
                        </td>`;
                }
                row += '</tr>';
                decisionMatrix.innerHTML += row;
            }
        }

        // Step 3: Generate decision matrix rows when Step 2 is completed
        document.getElementById('addCriterion').addEventListener('click', function() {
            // Count the number of alternatives and criteria
            const alternativesCount = document.querySelectorAll('.alternative').length;
            const criteriaCount = document.querySelectorAll('.criterion').length;

            // Generate decision matrix rows
            createDecisionMatrixRows(alternativesCount, criteriaCount);
        });

       // Function to create the decision matrix from user input
function createDecisionMatrix() {
    const decisionMatrix = [];
    const rows = document.querySelectorAll('.decision-matrix tr');
    let maxColumns = 0; // Menyimpan jumlah maksimum kolom
    for (let i = 1; i < rows.length; i++) {
        const cells = rows[i].querySelectorAll('td input');
        const rowValues = [];
        for (const cell of cells) {
            rowValues.push(parseFloat(cell.value));
        }
        decisionMatrix.push(rowValues);
        maxColumns = Math.max(maxColumns, rowValues.length); // Perbarui jumlah maksimum kolom
    }

    // Remove columns with identical values
    const columnsToRemove = [];
    if (maxColumns > 0) {
        for (let col = 0; col < maxColumns; col++) {
            let isIdentical = true;
            const firstValue = decisionMatrix[0][col];
            for (let row = 1; row < decisionMatrix.length; row++) {
                if (decisionMatrix[row][col] !== firstValue) {
                    isIdentical = false;
                    break;
                }
            }
            if (isIdentical) {
                columnsToRemove.push(col);
            }
        }
    }

    // Create a new matrix with identical columns removed
    const modifiedDecisionMatrix = decisionMatrix.map((row) => {
        return row.filter((_, col) => !columnsToRemove.includes(col));
    });

    return modifiedDecisionMatrix;
}



        // Function to get the weights from user input
        function getWeights() {
            const weightInputs = document.querySelectorAll('.weight');
            const weights = [];
            weightInputs.forEach(input => {
                weights.push(parseFloat(input.value));
            });
            return weights;
        }

        // Function to normalize the decision matrix
        function normalizeMatrix(matrix) {
            const normalizedMatrix = [];
            const rows = matrix.length;
            const columns = matrix[0].length;

            for (let j = 0; j < columns; j++) {
                const columnValues = matrix.map(row => row[j]);
                const min = Math.min(...columnValues);
                const max = Math.max(...columnValues);

                const normalizedColumn = columnValues.map(value => (value - min) / (max - min));
                normalizedMatrix.push(normalizedColumn);
            }

            return normalizedMatrix;
        }

        // Function to weight the normalized matrix
        function weightMatrix(normalizedMatrix, weights) {
            const weightedMatrix = [];
            for (let i = 0; i < normalizedMatrix.length; i++) {
                const weightedRow = normalizedMatrix[i].map((value, j) => value * weights[j]);
                weightedMatrix.push(weightedRow);
            }
            return weightedMatrix;
        }

        // Function to calculate the Concordance matrix
        function calculateConcordanceMatrix(weightedMatrix) {
            const concordanceMatrix = [];
            for (let i = 0; i < weightedMatrix.length; i++) {
                const concordanceRow = [];
                for (let j = 0; j < weightedMatrix.length; j++) {
                    if (i === j) {
                        concordanceRow.push(1);
                    } else {
                        const maxDifferences = weightedMatrix[i].map((value, k) => Math.abs(value - weightedMatrix[j][k]));
                        const sumMaxDifferences = maxDifferences.reduce((sum, diff) => sum + diff, 0);
                        concordanceRow.push(1 - sumMaxDifferences);
                    }
                }
                concordanceMatrix.push(concordanceRow);
            }
            return concordanceMatrix;
        }

        // Function to calculate the Disconcordance matrix
        function calculateDisconcordanceMatrix(weightedMatrix) {
            const disconcordanceMatrix = [];
            for (let i = 0; i < weightedMatrix.length; i++) {
                const disconcordanceRow = [];
                for (let j = 0; j < weightedMatrix.length; j++) {
                    if (i === j) {
                        disconcordanceRow.push(0);
                    } else {
                        const maxDifferences = weightedMatrix[i].map((value, k) => Math.abs(value - weightedMatrix[j][k]));
                        const maxDifference = Math.max(...maxDifferences);
                        disconcordanceRow.push(maxDifference);
                    }
                }
                disconcordanceMatrix.push(disconcordanceRow);
            }
            return disconcordanceMatrix;
        }


        // Function to calculate Electre 2 values for each alternative
        function calculateElectre2Values(concordanceMatrix, disconcordanceMatrix, q, p, v) {
            const electre2Values = [];
            const n = concordanceMatrix.length;

            for (let i = 0; i < n; i++) {
                let sumConcordance = 0;
                let sumDisconcordance = 0;
                for (let j = 0; j < n; j++) {
                    if (i !== j) {
                        sumConcordance += concordanceMatrix[i][j];
                        sumDisconcordance += disconcordanceMatrix[i][j];
                    }
                }

                // Check if the denominator (sumConcordance + sumDisconcordance) is zero before division
                if (sumConcordance + sumDisconcordance === 0) {
                    electre2Values.push(0); // Special case if the denominator is zero
                } else {
                    // Electre 2 calculation method with q, p, and v parameters
                    const electre2Value = (q * sumConcordance - p * sumDisconcordance) / (v * (sumConcordance + sumDisconcordance));
                    electre2Values.push(electre2Value);
                }
            }

            return electre2Values;
        }

// Function to find the best alternative
function findBestAlternative(electreValues) {
            let bestAlternative = -1;
            let maxElectreValue = -1;

            for (let i = 0; i < electreValues.length; i++) {
                if (electreValues[i] > maxElectreValue) {
                    maxElectreValue = electreValues[i];
                    bestAlternative = i;
                }
            }

            return bestAlternative;
        }

        // Add an event listener for the "Hitung Electre 2" button
        document.querySelector('form').addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent the form from submission

            // Perform calculations and display the results
            const decisionMatrix = createDecisionMatrix();
            const weights = getWeights();
            const q = parseFloat(document.getElementById('q').value);
            const p = parseFloat(document.getElementById('p').value);
            const v = parseFloat(document.getElementById('v').value);

            if (decisionMatrix.length === 0 || weights.length === 0 || isNaN(q) || isNaN(p) || isNaN(v)) {
                alert('Please add alternative and criteria data, provide weight values, and enter q, p, and v values before calculating Electre 2.');
                return; // Exit the function if data is incomplete
            }

            // Step 1: Normalize the Decision Matrix (e.g., min-max normalization)
            const normalizedMatrix = normalizeMatrix(decisionMatrix);

            // Step 2: Weight the Normalized Matrix
            const weightedMatrix = weightMatrix(normalizedMatrix, weights);

            // Step 3: Calculate Concordance and Disconcordance Matrices (e.g., Concordance index method)
            const concordanceMatrix = calculateConcordanceMatrix(weightedMatrix);
            const disconcordanceMatrix = calculateDisconcordanceMatrix(weightedMatrix);

            // Step 4: Calculate Electre 2 values for each alternative
            const electre2Values = calculateElectre2Values(concordanceMatrix, disconcordanceMatrix, q, p, v);

            // Display the results in the Electre 2 results table
            const electre2ResultsBody = document.getElementById('electre2-results-body');
            electre2ResultsBody.innerHTML = ''; // Clear previous results

            for (let i = 0; i < decisionMatrix.length; i++) {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>Alternatif ${i + 1}</td>
                    <td>${electre2Values[i]}</td>
                `;
                electre2ResultsBody.appendChild(row);
            }

            // Find and alert the best alternative
            const bestAlternative = findBestAlternative(electre2Values);
            if (bestAlternative !== -1) {
                alert(`Alternatif terbaik adalah Alternatif ${bestAlternative + 1}.`);
            } else {
                alert('Tidak ada alternatif terbaik yang ditemukan.');
            }
        });
    </script>
</body>
</html>
