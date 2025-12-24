<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataGulma extends Model
{
    protected $table = 'data_gulma';
    protected $guarded = [];

    protected $fillable = [
        'wilayah_id',
        'id_feature',
        'status_gulma',
        'persentase',
        'tanggal',
        'import_log_id'
    ];

    public function importLog()
    {
        return $this->belongsTo(ImportLog::class);
    }
}
