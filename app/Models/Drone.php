<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drone extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'lokasi',
        'tanggal_perencanaan',
        'pdf_path',
        'pdf_filename',
        'persen_gulma',
        'user_id',
    ];

    protected $casts = [
        'tanggal_perencanaan' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
