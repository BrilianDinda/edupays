<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChildModel extends Model
{
    protected $table = 'children';

    protected $fillable = [
        'nama',
        'uid_kartu',
        'saldo',
    ];
}