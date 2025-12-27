# Database Setup - Gamifikasi Literasi Keuangan

## 📋 Overview

Proyek ini menggunakan **Laravel Migrations** untuk struktur database dan **SQL Seeders** untuk data awal.

---

## 🗂️ File Structure

```
database/
├── migrations/          # ← Struktur tabel (CREATE TABLE)
│   └── *.php           # Generated dari: php artisan migrate:generate
│
└── seeders/
    ├── DatabaseSeeder.php    # Main seeder
    ├── SqlSeeder.php         # SQL file executor
    └── sql/
        ├── glk_db.sql                    # ✅ Data utama (hanya INSERT)
        ├── postman_seed_full.sql         # ✅ Data testing (hanya INSERT)
        ├── glk_db_original_backup.sql    # 🔒 Backup (dengan CREATE TABLE)
        └── clean_sql.py                  # 🛠️ Script pembersih SQL
```

---

## ⚙️ Setup Process

### 1️⃣ Generate Migrations dari Database Existing

```bash
# Install library
composer require --dev kitloong/laravel-migrations-generator

# Generate migrations
php artisan migrate:generate

# Migrations akan dibuat di database/migrations/
```

### 2️⃣ Run Migrations (Create Tables)

```bash
# Fresh migration (drop all + recreate)
php artisan migrate:fresh

# Atau rollback dulu
php artisan migrate:rollback
php artisan migrate
```

### 3️⃣ Run Seeders (Insert Data)

```bash
# Run semua seeders
php artisan db:seed

# Atau specific seeder
php artisan db:seed --class=SqlSeeder

# Fresh + Seed sekaligus
php artisan migrate:fresh --seed
```

---

## 📝 File SQL yang Sudah Dibersihkan

### ✅ `glk_db.sql` (Data Utama)
- **Sebelum:** 875 lines (dengan CREATE TABLE, DROP TABLE, dll)
- **Sesudah:** 185 lines (hanya INSERT INTO + LOCK/UNLOCK TABLES)
- **Isi:**
  - `auth_tokens` - 12 rows
  - `auth_users` - 5 rows (players)
  - `boardtiles` - 0 rows (kosong)
  - `cards` - data (risk, chance, quiz)
  - `config` - 1 row
  - Dan tabel lainnya...

### ✅ `postman_seed_full.sql` (Data Testing)
- **Status:** Sudah aman (tidak ada CREATE TABLE)
- **Isi:** Data untuk testing API endpoints
  - Users & Profiles
  - Board Tiles
  - Scenarios & Options
  - Cards (Risk, Chance, Quiz)
  - Game Sessions aktif

---

## 🛠️ Cara Membersihkan SQL File (Manual)

Jika ada file SQL baru yang perlu dibersihkan:

```bash
cd database/seeders/sql
python clean_sql.py
```

Script akan:
1. ✅ Hapus `DROP TABLE IF EXISTS`
2. ✅ Hapus `CREATE TABLE`
3. ✅ Hapus `ALTER TABLE`
4. ✅ Hapus metadata MySQL (`SET`, `/*!`, comment)
5. ✅ Simpan hanya `INSERT INTO`, `LOCK TABLES`, `UNLOCK TABLES`

---

## 🚨 Troubleshooting

### Error: "Table already exists"
**Penyebab:** File SQL masih mengandung `CREATE TABLE`

**Solusi:**
```bash
# Bersihkan file SQL dengan script
python database/seeders/sql/clean_sql.py

# Atau hapus manual bagian CREATE TABLE
```

### Error: "Foreign key constraint fails"
**Penyebab:** Urutan INSERT tidak sesuai relasi tabel

**Solusi:** SqlSeeder sudah handle dengan:
```php
DB::statement('SET FOREIGN_KEY_CHECKS=0;');
// ... run seeders ...
DB::statement('SET FOREIGN_KEY_CHECKS=1;');
```

### Error: "File not found: base_data.sql"
**Penyebab:** File SQL yang dipanggil tidak ada

**Solusi:** Edit `SqlSeeder.php`, pastikan hanya load file yang ada:
```php
$sqlFiles = [
    'glk_db.sql',
    'postman_seed_full.sql',
];
```

---

## 📊 Verification

Setelah seeding, cek data:

```bash
# Via artisan tinker
php artisan tinker
>>> \App\Models\AuthUser::count()
>>> \App\Models\Card::count()
>>> \App\Models\Scenario::count()

# Atau via MySQL
mysql> SELECT COUNT(*) FROM auth_users;
mysql> SELECT COUNT(*) FROM cards;
```

---

## 🔄 Reset Database

```bash
# Full reset: drop all + migrate + seed
php artisan migrate:fresh --seed

# Hanya reset data (tidak drop table)
php artisan db:seed --class=SqlSeeder
```

---

## 📚 Best Practices

1. ✅ **Migrations** untuk struktur (CREATE TABLE)
2. ✅ **Seeders** untuk data (INSERT INTO)
3. ✅ **Backup** file original sebelum edit
4. ✅ **Version control** migrations dan seeders
5. ❌ **Jangan** campur CREATE dan INSERT dalam satu file seeder

---

## 📞 Support

Jika ada masalah:
1. Check file di `storage/logs/laravel.log`
2. Run dengan verbose: `php artisan db:seed --class=SqlSeeder -vvv`
3. Test manual: `mysql -u root -p < database/seeders/sql/glk_db.sql`

---

✅ **Status Saat Ini:**
- Migrations: ✅ Generated
- glk_db.sql: ✅ Cleaned (hanya INSERT)
- postman_seed_full.sql: ✅ Safe (sudah bersih)
- SqlSeeder: ✅ Ready to use
