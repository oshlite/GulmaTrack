<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapPublication extends Model
{
    use HasFactory;

    protected $fillable = [
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

    public static function getLatestPublished()
    {
        return static::where('status', 'published')
            ->latest('published_at')
            ->first();
    }

    public static function isDataPublished()
    {
        $latest = static::getLatestPublished();
        return $latest !== null;
    }
}
