<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportLog extends Model
{
    protected $table = 'import_logs';
    protected $guarded = [];

    protected $fillable = [
        'nama_file',
        'wilayah_id',
        'tahun',
        'bulan',
        'minggu',
        'jumlah_records',
        'jumlah_berhasil',
        'jumlah_gagal',
        'status',
        'error_log',
        'user_id'
    ];

    protected $casts = [
        'error_log' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dataGulma()
    {
        return $this->hasMany(DataGulma::class);
    }
}
