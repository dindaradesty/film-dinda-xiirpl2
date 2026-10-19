<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cast extends Model
{
    use HasFactory;

    // Sesuai kolom tabel `casts`: name, age, bio
    protected $fillable = [
        'name',
        'age',
        'bio',
    ];

    public function films()
    {
        return $this->belongsToMany(Film::class, 'film_cast', 'cast_id', 'film_id');
    }
}