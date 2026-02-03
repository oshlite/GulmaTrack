<?php
// Simple database backup - export all data as SQL INSERT statements
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$database = env('DB_DATABASE', 'db_gulma');

echo "📦 Membuat backup database: $database\n";

// Get all tables
$tables = DB::select("
    SELECT tablename FROM pg_tables 
    WHERE schemaname = 'public'
    ORDER BY tablename
");

if (empty($tables)) {
    die("❌ Error: Tidak ada table!\n");
}

echo "Tables: " . count($tables) . "\n\n";

$backup = "-- PostgreSQL Database Backup\n";
$backup .= "-- Database: $database\n";
$backup .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
$backup .= "-- Total Records: \n\n";

$totalRecords = 0;
$tableNames = array_map(fn($t) => $t->tablename, $tables);

// Get data for each table
foreach ($tableNames as $table) {
    try {
        $rows = DB::table($table)->get();
        $count = $rows->count();
        
        if ($count > 0) {
            echo "  ✓ Table: $table ($count rows)\n";
            
            $backup .= "\n\n-- ===============================================\n";
            $backup .= "-- Data untuk table: $table ($count rows)\n";
            $backup .= "-- ===============================================\n\n";
            
            // Build INSERT statements
            foreach ($rows as $row) {
                $columns = array_keys((array)$row);
                $values = array_values((array)$row);
                
                // Escape values for SQL
                $escapedValues = array_map(function($v) {
                    if ($v === null) {
                        return 'NULL';
                    }
                    if (is_bool($v)) {
                        return $v ? 'true' : 'false';
                    }
                    if (is_numeric($v) && !is_string($v)) {
                        return $v;
                    }
                    return "'" . str_replace("'", "''", $v) . "'";
                }, $values);
                
                $cols = implode(', ', array_map(fn($c) => "\"$c\"", $columns));
                $vals = implode(', ', $escapedValues);
                $backup .= "INSERT INTO \"$table\" ($cols) VALUES ($vals);\n";
                
                $totalRecords++;
            }
        } else {
            echo "  ○ Table: $table (0 rows)\n";
        }
    } catch (Exception $e) {
        echo "  ✗ Table: $table - Error: " . $e->getMessage() . "\n";
    }
}

// Update total records count
$backup = str_replace("-- Total Records: \n", "-- Total Records: $totalRecords\n", $backup);

// Write backup file
$filename = "db_backup_2026-02-03.sql";
$bytes = file_put_contents($filename, $backup);

if ($bytes) {
    echo "\n✅ Backup berhasil dibuat!\n";
    echo "📄 File: $filename\n";
    echo "📊 Size: " . round($bytes / 1024, 2) . " KB\n";
    echo "📈 Total records: $totalRecords\n";
    echo "🕐 Waktu: " . date('Y-m-d H:i:s') . "\n";
} else {
    echo "❌ Error: Gagal menulis file!\n";
}
