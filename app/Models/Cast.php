<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cast extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'umur',
        'bio',
    ];

    public function films()
    {
        return $this->belongsToMany(Film::class, 'film_cast', 'cast_id', 'film_id');
    }
}