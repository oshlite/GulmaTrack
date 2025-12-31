<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class GulmaPhoto extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'kategori',
        'foto_path',
        'deskripsi',
        'uploaded_by',
        'file_size',
        'mime_type',
        'is_primary'
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    // Relationship
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // Accessor untuk URL foto
    public function getFotoUrlAttribute()
    {
        return Storage::url($this->foto_path);
    }

    // Accessor untuk ukuran file yang readable
    public function getFileSizeFormattedAttribute()
    {
        if (!$this->file_size) return 'N/A';
        
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    // Get photos by category
    public static function getByKategori($kategori, $limit = null)
    {
        $query = self::where('kategori', $kategori)
            ->with('uploader')
            ->orderBy('is_primary', 'desc')
            ->orderBy('created_at', 'desc');
        
        if ($limit) {
            return $query->limit($limit)->get();
        }
        
        return $query->get();
    }

    // Get primary photo for category
    public static function getPrimaryPhoto($kategori)
    {
        return self::where('kategori', $kategori)
            ->where('is_primary', true)
            ->first();
    }

    // Get stats
    public static function getStats()
    {
        return [
            'total_photos' => self::count(),
            'bersih_count' => self::where('kategori', 'bersih')->count(),
            'ringan_count' => self::where('kategori', 'ringan')->count(),
            'sedang_count' => self::where('kategori', 'sedang')->count(),
            'berat_count' => self::where('kategori', 'berat')->count(),
            'this_month' => self::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];
    }

    // Scope untuk filter
    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }
}
