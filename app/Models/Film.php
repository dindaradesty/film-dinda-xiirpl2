<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'sinopsis',
        'year',
        'genre_id',
        'poster',
    ];

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    // Menggunakan nama 'pemeran' agar tidak bentrok dengan method bawaan Laravel 'casts()'
    public function pemeran()
    {
        return $this->belongsToMany(Cast::class, 'film_cast', 'film_id', 'cast_id');
    }

    /**
     * Relasi ke tabel kritiks (komentar/ulasan film).
     */
    public function kritiks()
    {
        return $this->hasMany(Kritik::class, 'film_id');
    }
}