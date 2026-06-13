<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RfidScan extends Model
{
    protected $fillable = [
        'uid_rfid',
        'student_id',
        'scanned_at',
        'card_saldo',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
