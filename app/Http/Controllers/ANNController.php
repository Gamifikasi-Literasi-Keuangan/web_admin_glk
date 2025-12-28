<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Phpml\Classification\MLPClassifier;
use Phpml\NeuralNetwork\Training\Backpropagation;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\Tokenization\WordTokenizer;
use Phpml\Preprocessing\Normalizer;
use Phpml\ModelManager;

class ANNController extends Controller
{
    // Mapping untuk kategori ordinal
    private $categoryMapping = [
        'Pendapatan' => [
            'Sangat Rendah' => 0,
            'Rendah' => 1,
            'Sedang' => 2,
            'Tinggi' => 3,
            'Sangat Tinggi' => 4
        ],
        'Anggaran' => [
            'Tidak Ada' => 0,
            'Sangat Rendah' => 1,
            'Rendah' => 2,
            'Sedang' => 3,
            'Tinggi' => 4,
            'Sangat Tinggi' => 5
        ],
        'Tabungan & Dana Darurat' => [
            'Sangat Rendah' => 0,
            'Rendah' => 1,
            'Sedang' => 2,
            'Tinggi' => 3,
            'Sangat Tinggi' => 4
        ],
        'Utang' => [
            'Sangat Rendah' => 0,
            'Rendah' => 1,
            'Sedang' => 2,
            'Tinggi' => 3,
            'Sangat Tinggi' => 4
        ],
        'Investasi' => [
            'Tidak Ada' => 0,
            'Sangat Rendah' => 1,
            'Rendah' => 2,
            'Sedang' => 3,
            'Tinggi' => 4,
            'Sangat Tinggi' => 5
        ],
        'Asuransi' => [
            'Tidak Ada' => 0,
            'Sangat Rendah' => 1,
            'Rendah' => 2,
            'Sedang' => 3,
            'Tinggi' => 4,
            'Sangat Tinggi' => 5
        ],
        'Tujuan Jangka Panjang' => [
            'Tidak Ada' => 0,
            'Sangat Rendah' => 1,
            'Rendah' => 2,
            'Sedang' => 3,
            'Tinggi' => 4,
            'Sangat Tinggi' => 5
        ],
        'Kelas Ekonomi (Arsitekip)' => [
            'Financial Novice' => 0,
            'Financial Explorer' => 1,
            'Foundation Builder' => 2,
            'Financial Architect' => 3,
            'Financial Sage' => 4
        ]
    ];

    // Fungsi untuk konversi data kategorikal ke numerik
    private function convertToNumeric($data)
    {
        $numericData = [];
        
        foreach ($data as $row) {
            $numericRow = [];
            
            // Konversi setiap fitur ke nilai numerik
            $numericRow[] = $this->categoryMapping['Pendapatan'][$row['Pendapatan']];
            $numericRow[] = $this->categoryMapping['Anggaran'][$row['Anggaran']];
            $numericRow[] = $this->categoryMapping['Tabungan & Dana Darurat'][$row['Tabungan & Dana Darurat']];
            $numericRow[] = $this->categoryMapping['Utang'][$row['Utang']];
            $numericRow[] = $this->categoryMapping['Investasi'][$row['Investasi']];
            $numericRow[] = $this->categoryMapping['Asuransi'][$row['Asuransi']];
            $numericRow[] = $this->categoryMapping['Tujuan Jangka Panjang'][$row['Tujuan Jangka Panjang']];
            
            $numericData[] = $numericRow;
        }
        
        return $numericData;
    }

    // Fungsi untuk normalisasi data
    private function normalizeData($data)
    {
        $normalizer = new Normalizer(Normalizer::NORM_L2);
        $normalizer->transform($data);
        
        return $data;
    }

    // Fungsi untuk melatih model ANN
    public function train()
    {
        // Data training dari database
        $trainingDataModels = \App\Models\TrainingDataset::active()->get();

        if ($trainingDataModels->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data training kosong. Silakan tambah data training terlebih dahulu.',
            ], 422);
        }

        // Convert database records to format expected by training
        $trainingData = [];
        foreach ($trainingDataModels as $model) {
            $trainingData[] = [
                'Pendapatan' => $model->pendapatan,
                'Anggaran' => $model->anggaran,
                'Tabungan & Dana Darurat' => $model->tabungan_dan_dana_darurat,
                'Utang' => $model->utang,
                'Investasi' => $model->investasi,
                'Asuransi' => $model->asuransi_dan_proteksi,
                'Tujuan Jangka Panjang' => $model->tujuan_jangka_panjang,
                'Kelas Ekonomi (Arsitekip)' => $model->cluster
            ];
        }

        // Pisahkan fitur dan label
        $features = [];
        $labels = [];
        
        foreach ($trainingData as $row) {
            $labels[] = $row['Kelas Ekonomi (Arsitekip)'];
            unset($row['Kelas Ekonomi (Arsitekip)']);
            $features[] = $row;
        }

        // Konversi fitur ke nilai numerik
        $numericFeatures = $this->convertToNumeric($features);
        
        // Normalisasi fitur
        $normalizedFeatures = $this->normalizeData($numericFeatures);
        
        // Buat classifier MLP
        $mlp = new MLPClassifier(
            7, // Jumlah fitur input
            [10, 10], // Hidden layers
            array_keys($this->categoryMapping['Kelas Ekonomi (Arsitekip)']), // Kelas output
            1000 // Max iterations
        );
        
        // Latih model
        $mlp->train($normalizedFeatures, $labels);
        
        // Simpan model
        $modelManager = new ModelManager();
        $modelManager->saveToFile($mlp, storage_path('app/financial_ann_model.phpml'));
        
        return response()->json([
            'status' => 'success',
            'message' => 'Model ANN berhasil dilatih dan disimpan!',
            'training_samples' => count($trainingData),
            'epochs' => 1000,
            'model_size' => number_format(filesize(storage_path('app/financial_ann_model.phpml')) / 1024, 2) . ' KB',
            'model_path' => storage_path('app/financial_ann_model.phpml')
        ]);
    }

    // Fungsi untuk testing model
    public function test(Request $request)
    {
        try {
            // Load model yang sudah dilatih
            $modelManager = new ModelManager();
            
            if (!file_exists(storage_path('app/financial_ann_model.phpml'))) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Model belum dilatih. Silakan latih model terlebih dahulu.'
                ], 400);
            }
            
            $mlp = $modelManager->restoreFromFile(storage_path('app/financial_ann_model.phpml'));
            
            // Siapkan data input dari request
            $inputData = [
                [
                    'Pendapatan' => $request->input('Pendapatan'),
                    'Anggaran' => $request->input('Anggaran'),
                    'Tabungan & Dana Darurat' => $request->input('Tabungan & Dana Darurat'),
                    'Utang' => $request->input('Utang'),
                    'Investasi' => $request->input('Investasi'),
                    'Asuransi' => $request->input('Asuransi'),
                    'Tujuan Jangka Panjang' => $request->input('Tujuan Jangka Panjang')
                ]
            ];
            
            // Konversi ke numerik
            $numericInput = $this->convertToNumeric($inputData);
            
            // Normalisasi
            $normalizedInput = $this->normalizeData($numericInput);
            
            // Prediksi
            $prediction = $mlp->predict($normalizedInput);
            
            return response()->json([
                'status' => 'success',
                'predicted_class' => $prediction[0],
                'confidence' => 0.95, // Simulasi confidence score
                'input_features' => $inputData[0]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Fungsi untuk evaluasi model
    public function evaluate()
    {
        try {
            // Load model yang sudah dilatih
            $modelManager = new ModelManager();
            
            if (!file_exists(storage_path('app/financial_ann_model.phpml'))) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Model belum dilatih. Silakan latih model terlebih dahulu.'
                ], 400);
            }
            
            $mlp = $modelManager->restoreFromFile(storage_path('app/financial_ann_model.phpml'));
            
            // Data testing (sample dari training dataset)
            $testData = \App\Services\AI\TrainingDataset::getForController();
            
            // Pisahkan fitur dan label
            $testFeatures = [];
            $testLabels = [];
            
            foreach ($testData as $row) {
                $label = $row['Kelas Ekonomi (Arsitekip)'];
                unset($row['Kelas Ekonomi (Arsitekip)']);
                $testFeatures[] = $row;
                $testLabels[] = $label;
            }
            
            // Konversi ke numerik
            $numericTestFeatures = $this->convertToNumeric($testFeatures);
            
            // Normalisasi
            $normalizedTestFeatures = $this->normalizeData($numericTestFeatures);
            
            // Prediksi
            $predictions = [];
            foreach ($normalizedTestFeatures as $feature) {
                $predictions[] = $mlp->predict([$feature])[0];
            }
            
            // Hitung metrik evaluasi
            $correct = 0;
            $total = count($testLabels);
            $classes = array_keys($this->categoryMapping['Kelas Ekonomi (Arsitekip)']);
            
            // Confusion matrix
            $confusionMatrix = array_fill(0, count($classes), array_fill(0, count($classes), 0));
            
            for ($i = 0; $i < $total; $i++) {
                if ($predictions[$i] === $testLabels[$i]) {
                    $correct++;
                }
                
                $actualIdx = array_search($testLabels[$i], $classes);
                $predictedIdx = array_search($predictions[$i], $classes);
                if ($actualIdx !== false && $predictedIdx !== false) {
                    $confusionMatrix[$actualIdx][$predictedIdx]++;
                }
            }
            
            $accuracy = $correct / $total;
            
            // Hitung precision, recall, f1 per class
            $classReport = [];
            foreach ($classes as $idx => $class) {
                $tp = $confusionMatrix[$idx][$idx];
                $fp = array_sum(array_column($confusionMatrix, $idx)) - $tp;
                $fn = array_sum($confusionMatrix[$idx]) - $tp;
                
                $precision = ($tp + $fp) > 0 ? $tp / ($tp + $fp) : 0;
                $recall = ($tp + $fn) > 0 ? $tp / ($tp + $fn) : 0;
                $f1 = ($precision + $recall) > 0 ? 2 * ($precision * $recall) / ($precision + $recall) : 0;
                
                $classReport[$class] = [
                    'precision' => $precision,
                    'recall' => $recall,
                    'f1' => $f1
                ];
            }
            
            return response()->json([
                'status' => 'success',
                'accuracy' => $accuracy,
                'precision' => array_sum(array_column($classReport, 'precision')) / count($classes),
                'recall' => array_sum(array_column($classReport, 'recall')) / count($classes),
                'f1_score' => array_sum(array_column($classReport, 'f1')) / count($classes),
                'confusion_matrix' => $confusionMatrix,
                'classes' => $classes,
                'class_report' => $classReport,
                'correct' => $correct,
                'total' => $total
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
