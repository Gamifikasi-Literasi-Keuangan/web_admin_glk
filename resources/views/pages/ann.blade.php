<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ANN Management - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        @include('components.sidebar')

        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto">
        

            <!-- Content -->
            <div class="p-6">
                <!-- Header -->
                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-gray-800">
                        <i class="fas fa-brain text-purple-600"></i> Neural Network Management
                    </h1>
                    <p class="text-gray-600 mt-2">Train, test, and evaluate AI model untuk player clustering</p>
                </div>

                <!-- Model Status Card -->
                <div class="bg-gradient-to-r from-purple-600 to-blue-600 rounded-lg shadow-lg p-6 mb-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Model Status</h3>
                            <div id="modelStatus" class="flex items-center">
                                <div class="animate-pulse flex items-center">
                                    <i class="fas fa-spinner fa-spin mr-2"></i>
                                    <span>Checking model status...</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-bold mb-1" id="modelVersion">-</div>
                            <div class="text-sm opacity-90">Model Version</div>
                        </div>
                    </div>
                </div>

                <!-- Action Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Train Model Card -->
                    <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
                        <div class="flex items-center mb-4">
                            <div class="bg-green-100 p-3 rounded-lg">
                                <i class="fas fa-graduation-cap text-green-600 text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-800">Train Model</h3>
                                <p class="text-sm text-gray-600">Latih model dengan dataset</p>
                            </div>
                        </div>
                        <button onclick="trainModel()" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors">
                            <i class="fas fa-play mr-2"></i> Start Training
                        </button>
                    </div>

                    <!-- Test Model Card -->
                    <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
                        <div class="flex items-center mb-4">
                            <div class="bg-blue-100 p-3 rounded-lg">
                                <i class="fas fa-vial text-blue-600 text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-800">Test Model</h3>
                                <p class="text-sm text-gray-600">Uji prediksi model</p>
                            </div>
                        </div>
                        <button onclick="showTestModal()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors">
                            <i class="fas fa-flask mr-2"></i> Test Prediction
                        </button>
                    </div>

                    <!-- Evaluate Model Card -->
                    <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
                        <div class="flex items-center mb-4">
                            <div class="bg-purple-100 p-3 rounded-lg">
                                <i class="fas fa-chart-line text-purple-600 text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-800">Evaluate</h3>
                                <p class="text-sm text-gray-600">Analisis performa model</p>
                            </div>
                        </div>
                        <button onclick="evaluateModel()" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors">
                            <i class="fas fa-chart-bar mr-2"></i> View Metrics
                        </button>
                    </div>
                </div>

                <!-- Results Section -->
                <div id="resultsSection" class="hidden">
                    <!-- Training Results -->
                    <div id="trainingResults" class="bg-white rounded-lg shadow-lg p-6 mb-6 hidden">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">
                            <i class="fas fa-check-circle text-green-600"></i> Training Results
                        </h3>
                        <div id="trainingContent"></div>
                    </div>

                    <!-- Test Results -->
                    <div id="testResults" class="bg-white rounded-lg shadow-lg p-6 mb-6 hidden">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">
                            <i class="fas fa-flask text-blue-600"></i> Test Results
                        </h3>
                        <div id="testContent"></div>
                    </div>

                    <!-- Evaluation Results -->
                    <div id="evaluationResults" class="bg-white rounded-lg shadow-lg p-6 mb-6 hidden">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">
                            <i class="fas fa-chart-line text-purple-600"></i> Evaluation Metrics
                        </h3>
                        <div id="evaluationContent"></div>
                    </div>
                </div>

                <!-- Training Dataset Info -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-database text-gray-600"></i> Training Dataset
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-4">
                        <div class="text-center p-4 bg-red-50 rounded-lg">
                            <div class="text-2xl font-bold text-red-600">5</div>
                            <div class="text-sm text-gray-600">Financial Novice</div>
                        </div>
                        <div class="text-center p-4 bg-orange-50 rounded-lg">
                            <div class="text-2xl font-bold text-orange-600">6</div>
                            <div class="text-sm text-gray-600">Financial Explorer</div>
                        </div>
                        <div class="text-center p-4 bg-yellow-50 rounded-lg">
                            <div class="text-2xl font-bold text-yellow-600">7</div>
                            <div class="text-sm text-gray-600">Foundation Builder</div>
                        </div>
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">5</div>
                            <div class="text-sm text-gray-600">Financial Architect</div>
                        </div>
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <div class="text-2xl font-bold text-purple-600">2</div>
                            <div class="text-sm text-gray-600">Financial Sage</div>
                        </div>
                    </div>
                    <div class="text-center text-gray-600">
                        <strong>Total Samples: 25</strong> | Input Features: 7 | Output Classes: 5
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Test Modal -->
    <div id="testModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-2xl max-h-screen overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-vial text-blue-600"></i> Test Model Prediction
                </h3>
                <button onclick="closeTestModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <form id="testForm" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Pendapatan</label>
                        <select name="pendapatan" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="Sangat Rendah">Sangat Rendah</option>
                            <option value="Rendah">Rendah</option>
                            <option value="Sedang">Sedang</option>
                            <option value="Tinggi">Tinggi</option>
                            <option value="Sangat Tinggi">Sangat Tinggi</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Anggaran</label>
                        <select name="anggaran" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="Tidak Ada">Tidak Ada</option>
                            <option value="Sangat Rendah">Sangat Rendah</option>
                            <option value="Rendah">Rendah</option>
                            <option value="Sedang">Sedang</option>
                            <option value="Tinggi">Tinggi</option>
                            <option value="Sangat Tinggi">Sangat Tinggi</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tabungan & Dana Darurat</label>
                        <select name="tabungan" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="Sangat Rendah">Sangat Rendah</option>
                            <option value="Rendah">Rendah</option>
                            <option value="Sedang">Sedang</option>
                            <option value="Tinggi">Tinggi</option>
                            <option value="Sangat Tinggi">Sangat Tinggi</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Utang</label>
                        <select name="utang" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="Sangat Rendah">Sangat Rendah</option>
                            <option value="Rendah">Rendah</option>
                            <option value="Sedang">Sedang</option>
                            <option value="Tinggi">Tinggi</option>
                            <option value="Sangat Tinggi">Sangat Tinggi</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Investasi</label>
                        <select name="investasi" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="Tidak Ada">Tidak Ada</option>
                            <option value="Sangat Rendah">Sangat Rendah</option>
                            <option value="Rendah">Rendah</option>
                            <option value="Sedang">Sedang</option>
                            <option value="Tinggi">Tinggi</option>
                            <option value="Sangat Tinggi">Sangat Tinggi</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Asuransi & Proteksi</label>
                        <select name="asuransi" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="Tidak Ada">Tidak Ada</option>
                            <option value="Sangat Rendah">Sangat Rendah</option>
                            <option value="Rendah">Rendah</option>
                            <option value="Sedang">Sedang</option>
                            <option value="Tinggi">Tinggi</option>
                            <option value="Sangat Tinggi">Sangat Tinggi</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tujuan Jangka Panjang</label>
                        <select name="tujuan" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="Tidak Ada">Tidak Ada</option>
                            <option value="Sangat Rendah">Sangat Rendah</option>
                            <option value="Rendah">Rendah</option>
                            <option value="Sedang">Sedang</option>
                            <option value="Tinggi">Tinggi</option>
                            <option value="Sangat Tinggi">Sangat Tinggi</option>
                        </select>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="testModel()" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors">
                        <i class="fas fa-play mr-2"></i> Run Prediction
                    </button>
                    <button type="button" onclick="closeTestModal()" class="px-6 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-3 rounded-lg transition-colors">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const API_BASE = "{{ url('/api/admin') }}";
        const token = localStorage.getItem('admin_token');

        // Check model status on load
        document.addEventListener('DOMContentLoaded', function() {
            checkModelStatus();
        });

        async function checkModelStatus() {
            // Simulasi check - bisa diganti dengan endpoint real
            setTimeout(() => {
                const statusDiv = document.getElementById('modelStatus');
                const versionDiv = document.getElementById('modelVersion');
                
                statusDiv.innerHTML = `
                    <i class="fas fa-circle text-green-400 mr-2 animate-pulse"></i>
                    <span>Model Ready</span>
                `;
                versionDiv.textContent = 'v1.0';
            }, 1000);
        }

        async function trainModel() {
            const btn = event.target;
            const originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Training...';

            try {
                const response = await fetch(`${API_BASE}/ann/train`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    showTrainingResults(data);
                    showNotification('success', 'Model berhasil dilatih!');
                } else {
                    throw new Error(data.message || 'Training gagal');
                }
            } catch (error) {
                showNotification('error', error.message);
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            }
        }

        function showTestModal() {
            document.getElementById('testModal').classList.remove('hidden');
        }

        function closeTestModal() {
            document.getElementById('testModal').classList.add('hidden');
        }

        async function testModel() {
            const form = document.getElementById('testForm');
            const formData = new FormData(form);
            const data = {
                Pendapatan: formData.get('pendapatan'),
                Anggaran: formData.get('anggaran'),
                'Tabungan & Dana Darurat': formData.get('tabungan'),
                Utang: formData.get('utang'),
                Investasi: formData.get('investasi'),
                Asuransi: formData.get('asuransi'),
                'Tujuan Jangka Panjang': formData.get('tujuan')
            };

            try {
                const response = await fetch(`${API_BASE}/ann/test`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok) {
                    closeTestModal();
                    showTestResults(result);
                    showNotification('success', 'Prediksi berhasil!');
                } else {
                    throw new Error(result.message || 'Test gagal');
                }
            } catch (error) {
                showNotification('error', error.message);
            }
        }

        async function evaluateModel() {
            const btn = event.target;
            const originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Evaluating...';

            try {
                const response = await fetch(`${API_BASE}/ann/evaluate`, {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    showEvaluationResults(data);
                    showNotification('success', 'Evaluasi selesai!');
                } else {
                    throw new Error(data.message || 'Evaluation gagal');
                }
            } catch (error) {
                showNotification('error', error.message);
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            }
        }

        function showTrainingResults(data) {
            const section = document.getElementById('resultsSection');
            const results = document.getElementById('trainingResults');
            const content = document.getElementById('trainingContent');

            section.classList.remove('hidden');
            results.classList.remove('hidden');

            content.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div class="bg-green-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600 mb-1">Training Samples</div>
                        <div class="text-2xl font-bold text-green-600">${data.training_samples || 25}</div>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600 mb-1">Epochs</div>
                        <div class="text-2xl font-bold text-blue-600">${data.epochs || 1000}</div>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600 mb-1">Model Size</div>
                        <div class="text-2xl font-bold text-purple-600">${data.model_size || 'N/A'}</div>
                    </div>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-green-600 font-semibold">
                        <i class="fas fa-check-circle mr-2"></i>${data.message}
                    </p>
                </div>
            `;
        }

        function showTestResults(data) {
            const section = document.getElementById('resultsSection');
            const results = document.getElementById('testResults');
            const content = document.getElementById('testContent');

            section.classList.remove('hidden');
            results.classList.remove('hidden');

            const clusterColors = {
                'Financial Novice': 'red',
                'Financial Explorer': 'orange',
                'Foundation Builder': 'yellow',
                'Financial Architect': 'blue',
                'Financial Sage': 'purple'
            };

            const color = clusterColors[data.predicted_class] || 'gray';

            content.innerHTML = `
                <div class="bg-${color}-50 border-2 border-${color}-200 p-6 rounded-lg mb-4">
                    <div class="text-center">
                        <div class="text-sm text-gray-600 mb-2">Predicted Cluster</div>
                        <div class="text-3xl font-bold text-${color}-600 mb-2">${data.predicted_class}</div>
                        <div class="text-sm text-gray-600">Confidence: ${(data.confidence * 100).toFixed(1)}%</div>
                    </div>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-semibold mb-2">Input Features:</h4>
                    <div class="grid grid-cols-2 gap-2 text-sm">
                        ${Object.entries(data.input_features || {}).map(([key, value]) => `
                            <div class="flex justify-between">
                                <span class="text-gray-600">${key}:</span>
                                <span class="font-semibold">${value}</span>
                            </div>
                        `).join('')}
                    </div>
                </div>
            `;
        }

        function showEvaluationResults(data) {
            const section = document.getElementById('resultsSection');
            const results = document.getElementById('evaluationResults');
            const content = document.getElementById('evaluationContent');

            section.classList.remove('hidden');
            results.classList.remove('hidden');

            content.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-green-50 p-4 rounded-lg text-center">
                        <div class="text-sm text-gray-600 mb-1">Accuracy</div>
                        <div class="text-3xl font-bold text-green-600">${(data.accuracy * 100).toFixed(1)}%</div>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-lg text-center">
                        <div class="text-sm text-gray-600 mb-1">Precision</div>
                        <div class="text-3xl font-bold text-blue-600">${(data.precision * 100).toFixed(1)}%</div>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg text-center">
                        <div class="text-sm text-gray-600 mb-1">Recall</div>
                        <div class="text-3xl font-bold text-purple-600">${(data.recall * 100).toFixed(1)}%</div>
                    </div>
                    <div class="bg-yellow-50 p-4 rounded-lg text-center">
                        <div class="text-sm text-gray-600 mb-1">F1 Score</div>
                        <div class="text-3xl font-bold text-yellow-600">${(data.f1_score * 100).toFixed(1)}%</div>
                    </div>
                </div>
                
                ${data.confusion_matrix ? `
                <div class="bg-gray-50 p-4 rounded-lg mb-4">
                    <h4 class="font-semibold mb-3">Confusion Matrix</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-200">
                                    <th class="p-2 text-left">Actual / Predicted</th>
                                    ${data.classes.map(c => `<th class="p-2 text-center">${c}</th>`).join('')}
                                </tr>
                            </thead>
                            <tbody>
                                ${data.confusion_matrix.map((row, i) => `
                                    <tr class="border-t">
                                        <td class="p-2 font-semibold">${data.classes[i]}</td>
                                        ${row.map(val => `<td class="p-2 text-center">${val}</td>`).join('')}
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
                ` : ''}
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-semibold mb-2">Class Performance</h4>
                    <div class="space-y-2">
                        ${Object.entries(data.class_report || {}).map(([className, metrics]) => `
                            <div class="flex items-center justify-between p-2 bg-white rounded">
                                <span class="font-medium">${className}</span>
                                <div class="flex gap-4 text-sm">
                                    <span>P: ${(metrics.precision * 100).toFixed(0)}%</span>
                                    <span>R: ${(metrics.recall * 100).toFixed(0)}%</span>
                                    <span>F1: ${(metrics.f1 * 100).toFixed(0)}%</span>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            `;
        }

        function showNotification(type, message) {
            const colors = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                info: 'bg-blue-500'
            };

            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in`;
            notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} mr-2"></i>
                ${message}
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
    </script>
</body>
</html>
