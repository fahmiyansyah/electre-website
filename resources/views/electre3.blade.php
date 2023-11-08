<!DOCTYPE html>
<html>
<head>
<title>Decision Support System</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body {
            background-color: #f0f8ff;
        }

        .header {
            background-color: #87cefa;
            padding: 20px;
            text-align: center;
        }

        h1,
        h2 {
            color: #191970;
            text-align: center;
            padding-top: 20px;
        }

        h3 {
            color: #191970;
            text-align: center;
            padding-top: 20px; 
        }

        form {
            margin: 20px auto;
            width: 50%;
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #191970;
            border-radius: 5px;
        }

        form label {
            display: block;
            margin-top: 10px;
            color: #191970;
        }

        form input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #191970;
        }

        button[type="submit"] {
            display: block;
            width: 100%;
            background-color: #191970;
            color: #ffffff;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .alternatives-section,
        .criteria-section {
            margin: 20px auto;
            width: 50%;
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #191970;
            border-radius: 5px;
        }

        .alternatives-section label,
        .criteria-section label {
            display: block;
            margin-top: 10px;
            color: #191970;
        }

        .alternatives-section input,
        .criteria-section input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #191970;
        }

        .decision-matrix {
            margin: 20px auto;
            width: 70%;
        }

        .decision-matrix th,
        .decision-matrix td {
            border: 1px solid #191970;
            padding: 8px;
            text-align: center;
        }

        #electre3-results {
            margin: 20px auto;
            width: 50%;
            border-collapse: collapse;
        }

        #electre3-results th,
        #electre3-results td {
            border: 1px solid #191970;
            padding: 8px;
            text-align: center;
        }
    </style>
    <title>Decision Support System</title>
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

    <h2>Hasil Perhitungan Electre 3</h2>
    <table id="electre3-results">
        <h3>Alternatif Nilai Electre 3</h3>
        <tbody id="electre3-results-body">
            <!-- Hasil perhitungan Electre 3 akan ditampilkan di sini -->
        </tbody>
    </table>

    <form>
        <label for="q">Nilai q:</label>
        <input type="number" name="q" id="q" class="form-control" step="0.01">
        <label for="p">Nilai p:</label>
        <input type="number" name="p" id="p" class "form-control" step="0.01">
        <label for="v">Nilai v:</label>
        <input type="number" name="v" id="v" class="form-control" step="0.01">
        <button type="submit">Hitung Electre 3</button>
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
            for (let i = 1; i < rows.length; i++) {
                const cells = rows[i].querySelectorAll('td input');
                const rowValues = [];
                for (const cell of cells) {
                    rowValues.push(parseFloat(cell.value));
                }
                decisionMatrix.push(rowValues);
            }
            return decisionMatrix;
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
            const minValues = Array(columns).fill(Number.POSITIVE_INFINITY);
            const maxValues = Array(columns).fill(Number.NEGATIVE_INFINITY);

            // Find the minimum and maximum values for each column
            for (let i = 0; i < rows; i++) {
                for (let j = 0; j < columns; j++) {
                    minValues[j] = Math.min(minValues[j], matrix[i][j]);
                    maxValues[j] = Math.max(maxValues[j], matrix[i][j]);
                }
            }

            // Normalize the values using the min-max method
            for (let i = 0; i < rows; i++) {
                const normalizedRow = [];
                for (let j = 0; j < columns; j++) {
                    const normalizedValue = (matrix[i][j] - minValues[j]) / (maxValues[j] - minValues[j]);
                    normalizedRow.push(normalizedValue);
                }
                normalizedMatrix.push(normalizedRow);
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

        // Function to calculate Electre 3 values for each alternative
        function calculateElectre3Values(normalizedMatrix, weights, q, p, v) {
            const electre3Values = [];
            const n = normalizedMatrix.length;

            for (let i = 0; i < n; i++) {
                let sumPositiveDifferences = 0;
                let sumNegativeDifferences = 0;
                for (let j = 0; j < n; j++) {
                    if (i !== j) {
                        let positiveDiff = 0;
                        let negativeDiff = 0;
                        for (let k = 0; k < normalizedMatrix[i].length; k++) {
                            const diff = normalizedMatrix[j][k] - normalizedMatrix[i][k];
                            if (diff > 0) {
                                positiveDiff += weights[k] * diff;
                            } else {
                                negativeDiff += weights[k] * -diff;
                            }
                        }
                        if (positiveDiff >= q && negativeDiff <= p) {
                            sumPositiveDifferences += 1;
                        }
                        if (negativeDiff >= v) {
                            sumNegativeDifferences += 1;
                        }
                    }
                }

                electre3Values.push(sumPositiveDifferences / (n - 1) - sumNegativeDifferences / (n - 1));
            }

            return electre3Values;
        }

        // Function to find the best alternative
        function findBestAlternative(electre3Values) {
            let bestAlternative = -1;
            let maxElectreValue = -1;

            for (let i = 0; i < electre3Values.length; i++) {
                if (electre3Values[i] > maxElectreValue) {
                    maxElectreValue = electre3Values[i];
                    bestAlternative = i;
                }
            }

            return bestAlternative;
        }

        // Add an event listener for the "Hitung Electre 3" button
        document.querySelector('form').addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent the form from submission

            // Perform calculations and display the results
            const decisionMatrix = createDecisionMatrix();
            const weights = getWeights();
            const q = parseFloat(document.getElementById('q').value);
            const p = parseFloat(document.getElementById('p').value);
            const v = parseFloat(document.getElementById('v').value);

            if (decisionMatrix.length === 0 || weights.length === 0 || isNaN(q) || isNaN(p) || isNaN(v)) {
                alert('Please add alternative and criteria data, provide weight values, and enter q, p, and v values before calculating Electre 3.');
                return; // Exit the function if data is incomplete
            }

            // Step 1: Normalize the Decision Matrix
            const normalizedMatrix = normalizeMatrix(decisionMatrix);

            // Step 2: Weight the Normalized Matrix
            const weightedMatrix = weightMatrix(normalizedMatrix, weights);

            // Step 3: Calculate Electre 3 values for each alternative
            const electre3Values = calculateElectre3Values(normalizedMatrix, weights, q, p, v);

            // Display the results in the Electre 3 results table
            const electre3ResultsBody = document.getElementById('electre3-results-body');
            electre3ResultsBody.innerHTML = ''; // Clear previous results

            for (let i = 0; i < decisionMatrix.length; i++) {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>Alternatif ${i + 1}</td>
                    <td>${electre3Values[i]}</td>
                `;
                electre3ResultsBody.appendChild(row);
            }

            // Find and alert the best alternative
            const bestAlternative = findBestAlternative(electre3Values);
            if (bestAlternative !== -1) {
                alert(`Alternatif terbaik adalah Alternatif ${bestAlternative + 1}.`);
            } else {
                alert('Tidak ada alternatif terbaik yang ditemukan.');
            }
        });
    </script>
</body>
</html>
