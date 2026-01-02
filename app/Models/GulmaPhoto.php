<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class GulmaPhoto extends Model
{
    use SoftDeletes;

    /**
     * Table name
     */
    protected $table = 'gulma_photos';

    /**
     * Mass assignable fields
     */
    protected $fillable = [
        'kategori',
        'foto_path',
        'is_primary',
        'deskripsi',
        'uploaded_by',
        'file_size',
        'mime_type',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'is_primary' => 'boolean',
    ];

    /**
     * ======================
     * RELATIONSHIPS
     * ======================
     */

    /**
     * User who uploaded the photo
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * ======================
     * ACCESSORS
     * ======================
     */

    /**
     * Get full photo URL
     */
    public function getFotoUrlAttribute()
    {
        if (!$this->foto_path) {
            return '/image/roblox.png'; // Fallback placeholder
        }
        
        // Return public API route untuk serve image file langsung
        return route('api.gallery.image', ['id' => $this->id]);
    }

    /**
     * Get formatted file size
     */
    public function getFileSizeFormattedAttribute()
    {
        if (!$this->file_size) {
            return null;
        }

        $bytes = (int) $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes >= 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * ======================
     * QUERY SCOPES
     * ======================
     */

    /**
     * Filter by kategori
     */
    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    /**
     * Get stats for dashboard
     */
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
}
