<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    protected $table = 'foods';

    protected $fillable = [
        'nama_makanan',
        'harga',
        'kalori',
        'gula',
        'lemak',
        'komposisi',
        'foto'
    ];
}