<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'nama',
        'kelas',
        'uid_rfid',
        'saldo',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}