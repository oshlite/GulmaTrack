<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapPublication extends Model
{
    use HasFactory;

    protected $fillable = [
        'import_log_id',
        'tahun',
        'bulan',
        'minggu',
        'status',
        'published_at',
        'published_by',
        'notes'
    ];

    protected $casts = [
        'published_at' => 'datetime'
    ];

    public function publisher()
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    public function importLog()
    {
        return $this->belongsTo(ImportLog::class, 'import_log_id');
    }

    public static function getLatestPublished($tahun = null, $bulan = null, $minggu = null)
    {
        $query = static::where('status', 'published');
        
        // Jika periode spesifik, gunakan itu
        if ($tahun && $bulan && $minggu) {
            $query->where('tahun', $tahun)
                  ->where('bulan', $bulan)
                  ->where('minggu', $minggu);
        } else {
            // Jika tidak spesifik, ambil yang paling baru
            $query->orderBy('published_at', 'desc');
        }
        
        return $query->first();
    }

    public static function isDataPublished($tahun = null, $bulan = null, $minggu = null)
    {
        $latest = static::getLatestPublished($tahun, $bulan, $minggu);
        return $latest !== null;
    }
    
    /**
     * Get published publication for specific period
     */
    public static function getPublishedForPeriod($tahun, $bulan, $minggu)
    {
        return static::where('tahun', $tahun)
            ->where('bulan', $bulan)
            ->where('minggu', $minggu)
            ->where('status', 'published')
            ->first();
    }
}
