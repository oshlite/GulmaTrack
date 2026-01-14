<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataGulma extends Model
{
    use HasFactory;

    protected $table = 'data_gulma';

    protected $guarded = [];

    protected $fillable = [
        'wilayah_id',
        'id_feature',
        'status_gulma',
        'persentase',
        'import_log_id',
        // Kolom CSV sesuai format: PG,FM,WIL,SEKSI,NETO,HASIL,uMUR,TNM_STS,ACTIVITAS,KATEGORI,TANGGAL,TK/HA,TOTAL_TK
        'pg',
        'fm',
        'seksi',
        'neto',
        'hasil',
        'umur', // uMUR di CSV
        'tnm_sts', // TNM_STS di CSV
        'activitas', // ACTIVITAS di CSV
        'kategori', // KATEGORI di CSV
        'tanggal', // TANGGAL di CSV (date)
        'tk_ha', // TK/HA di CSV
        'total_tk' // TOTAL_TK di CSV
    ];

    protected $casts = [
        'tanggal' => 'date',
        'neto' => 'decimal:2',
        'hasil' => 'decimal:2',
        'tk_ha' => 'decimal:2',
        'umur' => 'decimal:2',
        'total_tk' => 'decimal:2',
        'persentase' => 'integer',
        'wilayah_id' => 'integer'
    ];

    /**
     * Relationship: Import Log
     */
    public function importLog()
    {
        return $this->belongsTo(ImportLog::class, 'import_log_id');
    }

    /**
     * Relationship: Wilayah (jika ada model Wilayah)
     */
    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'wilayah_id');
    }

    /**
     * Scope: Filter by periode (tahun, bulan, minggu)
     */
    public function scopeByPeriode($query, $tahun = null, $bulan = null, $minggu = null)
    {
        if ($tahun) {
            $query->whereHas('importLog', function($q) use ($tahun) {
                $q->where('tahun', $tahun);
            });
        }
        if ($bulan) {
            $query->whereHas('importLog', function($q) use ($bulan) {
                $q->where('bulan', $bulan);
            });
        }
        if ($minggu) {
            $query->whereHas('importLog', function($q) use ($minggu) {
                $q->where('minggu', $minggu);
            });
        }
        return $query;
    }

    /**
     * Scope: Filter by wilayah
     */
    public function scopeByWilayah($query, $wilayahId)
    {
        return $query->where('wilayah_id', $wilayahId);
    }

    /**
     * Scope: Get latest data only (successful imports)
     */
    public function scopeLatestData($query)
    {
        return $query->whereHas('importLog', function($q) {
            $q->where('status', 'success')
              ->latest('created_at');
        });
    }

    /**
     * Scope: Filter by kategori
     */
    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    /**
     * Scope: High productivity (hasil > 9)
     */
    public function scopeHighProductivity($query)
    {
        return $query->where('hasil', '>', 9);
    }

    /**
     * Scope: Medium productivity (hasil 8-9)
     */
    public function scopeMediumProductivity($query)
    {
        return $query->whereBetween('hasil', [8, 9]);
    }

    /**
     * Scope: Low productivity (hasil < 8)
     */
    public function scopeLowProductivity($query)
    {
        return $query->where('hasil', '<', 8);
    }

    /**
     * Accessor: Get formatted hasil
     */
    public function getFormattedHasilAttribute()
    {
        return number_format($this->hasil, 2, ',', '.') . ' T/Ha';
    }

    /**
     * Accessor: Get formatted neto
     */
    public function getFormattedNetoAttribute()
    {
        return number_format($this->neto, 2, ',', '.') . ' Ha';
    }

    /**
     * Accessor: Get kategori color
     */
    public function getKategoriColorAttribute()
    {
        $colors = [
            'Bersih' => '#3498db',
            'Ringan' => '#128241',
            'Sedang' => '#f1c40f',
            'Berat' => '#e74c3c'
        ];

        return $colors[$this->kategori] ?? '#ecf0f1';
    }

    /**
     * Static: Get total hasil for wilayah
     */
    public static function getTotalHasilByWilayah($wilayahId)
    {
        return self::where('wilayah_id', $wilayahId)->sum('hasil');
    }

    /**
     * Static: Get average hasil for wilayah
     */
    public static function getAverageHasilByWilayah($wilayahId)
    {
        return self::where('wilayah_id', $wilayahId)->avg('hasil');
    }

    /**
     * Static: Get statistics summary
     */
    public static function getStatisticsSummary()
    {
        return [
            'total_data' => self::count(),
            'total_wilayah' => self::distinct('wilayah_id')->count('wilayah_id'),
            'total_features' => self::distinct('id_feature')->count('id_feature'),
            'total_hasil' => self::sum('hasil'),
            'avg_hasil' => self::avg('hasil'),
            'total_neto' => self::sum('neto'),
            'total_tk' => self::sum('total_tk')
        ];
    }
}