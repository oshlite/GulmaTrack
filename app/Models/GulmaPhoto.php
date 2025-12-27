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
        'wilayah_id',
        'lokasi',
        'foto_path',
        'status_gulma',
        'tanggal_foto',
        'deskripsi',
        'uploaded_by',
        'file_size',
        'mime_type',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'tanggal_foto' => 'date',
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
        return Storage::disk('public')->url($this->foto_path);
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
     * Filter by wilayah
     */
    public function scopeWilayah($query, $wilayah)
    {
        return $query->where('wilayah_id', $wilayah);
    }

    /**
     * Filter by status gulma
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status_gulma', $status);
    }

    /**
     * Search by lokasi or deskripsi
     */
    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('lokasi', 'like', "%{$keyword}%")
              ->orWhere('deskripsi', 'like', "%{$keyword}%");
        });
    }

    /**
     * ======================
     * STATIC HELPERS
     * ======================
     */

    /**
     * Get photos by wilayah & lokasi
     */
    public static function getByLokasi($wilayah, $lokasi)
    {
        return self::where('wilayah_id', $wilayah)
            ->where('lokasi', $lokasi)
            ->orderBy('tanggal_foto', 'desc')
            ->get();
    }

    /**
     * Gallery statistics
     */
    public static function getStats()
    {
        return [
            'total' => self::count(),
            'bersih' => self::where('status_gulma', 'bersih')->count(),
            'ringan' => self::where('status_gulma', 'ringan')->count(),
            'sedang' => self::where('status_gulma', 'sedang')->count(),
            'berat' => self::where('status_gulma', 'berat')->count(),
            'latest_upload' => self::latest()->first()?->created_at,
        ];
    }
}
