<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Food;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use App\Models\Student;

class TransactionController extends Controller
{
    public function store(Request $request)
{
    $food = Food::find($request->food_id);

    $student = Student::find($request->student_id);

    if (!$food || !$student) {

        return response()->json([
            'success' => false,
            'message' => 'Data tidak ditemukan'
        ]);
    }

    // cek saldo
    if ($student->saldo < $food->harga) {

        return response()->json([
            'success' => false,
            'message' => 'Saldo tidak cukup'
        ], 400);
    }

    // potong saldo
    $student->saldo -= $food->harga;
    $student->save();

// simpan transaksi
     Transaction::create([
         'student_id' => $student->id,
         'nama_makanan' => $food->nama_makanan,
         'harga' => $food->harga,
         'waktu' => now(),
         'foto' => $food->foto,
     ]);

    return response()->json([
        'success' => true,
        'message' => 'Transaksi berhasil',
        'saldo' => $student->saldo
    ]);
}
public function topup(Request $request)
{
    $student = Student::find($request->student_id);

    if (!$student) {

        return response()->json([
            'success' => false,
            'message' => 'Student tidak ditemukan'
        ]);
    }

    $student->saldo += $request->nominal;

    $student->save();

    return response()->json([
        'success' => true,
        'message' => 'Topup berhasil',
        'saldo' => $student->saldo
    ]);
}
}