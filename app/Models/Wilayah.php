<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    protected $table = 'wilayah';
    protected $guarded = [];

    protected $fillable = [
        'wilayah_id',
        'nama_wilayah',
        'deskripsi'
    ];

    public function dataGulma()
    {
        return $this->hasMany(DataGulma::class, 'wilayah_id', 'wilayah_id');
    }
}