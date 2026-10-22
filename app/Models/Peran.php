<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peran extends Model
{
    use HasFactory;
    protected $table = 'perans';
    protected $fillable = ['actor', 'film_id', 'cast_id'];

    /**
     * Relasi ke tabel casts (pemeran).
     */
    public function cast()
    {
        return $this->belongsTo(Cast::class, 'cast_id');
    }

    /**
     * Relasi ke tabel films.
     */
    public function film()
    {
        return $this->belongsTo(Film::class, 'film_id');
    }
}
