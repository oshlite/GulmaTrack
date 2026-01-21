<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

DB::table('data_gulma')->truncate();
echo "✓ Data tabel data_gulma telah dihapus\n";
