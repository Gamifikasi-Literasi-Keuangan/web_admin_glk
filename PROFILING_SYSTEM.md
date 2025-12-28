# Profiling System Documentation

## Overview
Sistem profiling untuk mengevaluasi literasi keuangan user berdasarkan jawaban terhadap pertanyaan yang terhubung dengan aspek keuangan (Fuzzy Logic).

## Database Schema

### 1. `financial_aspects` (Static - No CRUD)
Aspek keuangan yang digunakan untuk penilaian fuzzy logic.

**Columns:**
- `id` - Primary key
- `aspect_key` - Unique identifier (varchar 100)
- `display_name` - Display name (varchar 150)
- `created_at`, `updated_at` - Timestamps

**Data (7 aspects):**
```
1. pendapatan - Pendapatan
2. anggaran - Anggaran
3. tabungan_dan_dana_darurat - Tabungan & Dana Darurat
4. utang - Utang
5. asuransi_dan_proteksi - Asuransi & Proteksi
6. investasi - Investasi
7. tujuan_jangka_panjang - Tujuan Jangka Panjang
```

### 2. `profiling_questions`
Pertanyaan profiling dengan skor maksimal.

**Columns:**
- `id` - Primary key
- `question_code` - Unique code (varchar 50)
- `question_text` - Teks pertanyaan
- `max_score` - Skor maksimal (int)
- `is_active` - Status aktif (boolean, default: true)
- `created_at`, `updated_at` - Timestamps

**Data (3 questions):**
```
Q1 (max_score: 40): "Apakah Anda menabung sebelum mengeluarkan uang?"
Q2 (max_score: 35): "Berapa rasio total cicilan terhadap pendapatan bulanan Anda?"
Q3 (max_score: 25): "Apakah Anda berinvestasi secara rutin dan teratur?"
```

### 3. `profiling_question_options`
Opsi jawaban untuk setiap pertanyaan dengan skor.

**Columns:**
- `id` - Primary key
- `question_id` - Foreign key ke profiling_questions (cascade delete)
- `option_code` - Kode opsi (A, B, C, D, E)
- `option_text` - Teks opsi
- `score` - Skor untuk opsi ini
- `created_at`, `updated_at` - Timestamps

**Options per Question:**
- Q1: 5 options (score: 0, 10, 20, 30, 40)
- Q2: 5 options (score: 0, 9, 18, 26, 35)
- Q3: 5 options (score: 0, 6, 13, 19, 25)

### 4. `profiling_question_aspects`
Many-to-many relationship antara questions dan financial aspects.

**Columns:**
- `id` - Primary key
- `question_id` - Foreign key ke profiling_questions (cascade delete)
- `aspect_id` - Foreign key ke financial_aspects (cascade delete)
- `created_at`, `updated_at` - Timestamps
- Unique constraint: (`question_id`, `aspect_id`)

**Question-Aspect Mapping:**
```
Q1 -> Pendapatan, Anggaran, Tabungan & Dana Darurat (3 aspects)
Q2 -> Utang, Asuransi & Proteksi (2 aspects)
Q3 -> Investasi, Tujuan Jangka Panjang (2 aspects)
```

## Models

### FinancialAspect
```php
// Relationships
questions() // BelongsToMany to ProfilingQuestion

// Methods
getAllKeys() // Static method, returns array of aspect_keys
```

### ProfilingQuestion
```php
// Relationships
options() // HasMany to ProfilingQuestionOption
aspects() // BelongsToMany to FinancialAspect

// Scopes
active() // Where is_active = true
withRelations() // Eager load options and aspects

// Casts
is_active: boolean
max_score: integer
```

### ProfilingQuestionOption
```php
// Relationships
question() // BelongsTo ProfilingQuestion

// Casts
score: integer
```

### ProfilingQuestionAspect
```php
// Relationships
question() // BelongsTo ProfilingQuestion
aspect() // BelongsTo FinancialAspect
```

## Seeder Usage

### Run Migration
```bash
php artisan migrate
```

### Run Seeder
```bash
php artisan db:seed --class=ProfilingSystemSeeder
```

### Full Database Seeding
```bash
php artisan db:seed
```

This will seed:
- 7 Financial Aspects (static)
- 3 Profiling Questions
- 15 Question Options (5 per question)
- 7 Question-Aspect relationships

## Query Examples

### Get all active questions with options and aspects
```php
$questions = ProfilingQuestion::active()
    ->withRelations()
    ->get();
```

### Get specific question with relations
```php
$question = ProfilingQuestion::with(['options', 'aspects'])
    ->where('question_code', 'q1')
    ->first();
```

### Get all financial aspects
```php
$aspects = FinancialAspect::all();
```

### Get questions for specific aspect
```php
$aspect = FinancialAspect::where('aspect_key', 'pendapatan')
    ->with('questions')
    ->first();

$questions = $aspect->questions;
```

### Calculate total score from answers
```php
$answers = [1, 2, 3]; // option IDs
$totalScore = ProfilingQuestionOption::whereIn('id', $answers)
    ->sum('score');
```

## Fuzzy Logic Integration

Sistem ini dirancang untuk mendukung fuzzy logic dengan:

1. **Weighted Scoring**: Setiap question memiliki `max_score` berbeda
   - Q1: 40 points (highest weight)
   - Q2: 35 points
   - Q3: 25 points
   - Total: 100 points

2. **Aspect Mapping**: Setiap question terhubung dengan multiple aspects
   - Memungkinkan scoring per aspect berdasarkan jawaban
   - Mendukung fuzzy membership calculation

3. **Normalized Scoring**: Score per option sudah disesuaikan dengan max_score
   - Q1 options: 0-40 (step: 10)
   - Q2 options: 0-35 (step: ~9)
   - Q3 options: 0-25 (step: ~6)

## File Structure

```
database/
├── migrations/
│   └── 2025_12_27_083647_create_profiling_system_tables.php
└── seeders/
    ├── DatabaseSeeder.php (updated)
    └── ProfilingSystemSeeder.php

app/
└── Models/
    ├── FinancialAspect.php
    ├── ProfilingQuestion.php
    ├── ProfilingQuestionOption.php
    └── ProfilingQuestionAspect.php
```

## Notes

- **FinancialAspect** adalah data statis - tidak ada CRUD operations
- Questions dapat di-enable/disable dengan field `is_active`
- Cascade delete: Jika question dihapus, options dan aspect mappings ikut terhapus
- Unique constraint pada question-aspect relationship mencegah duplikasi

## Future Enhancements

- Admin UI untuk manage questions & options
- API endpoints untuk profiling questionnaire
- Fuzzy logic calculation service
- Player profiling result storage
- Historical profiling data tracking
