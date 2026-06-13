<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\Transaction;
use App\Models\Student;
use App\Models\RfidScan;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    public function index()
    {
        $foods = Food::all();

        return response()->json([
            'success' => true,
            'data' => $foods
        ]);
    }

    public function saldo(Request $request)
    {
        $uid = $request->uid;

        $student = Student::where('uid_rfid', $uid)->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Kartu tidak dikenal'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'nama' => $student->nama,
            'saldo' => $student->saldo,
        ]);
    }

    public function buy(Request $request, $id)
    {
        $uid = $request->uid;

        $student = Student::where('uid_rfid', $uid)->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Kartu tidak ditemukan'
            ]);
        }

        $food = Food::find($id);

        if (!$food) {
            return response()->json([
                'success' => false,
                'message' => 'Makanan tidak ditemukan'
            ]);
        }

        if ($student->saldo < $food->harga) {
            return response()->json([
                'success' => false,
                'message' => 'Saldo tidak cukup'
            ]);
        }

        $student->saldo -= $food->harga;
        $student->save();

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
            'saldo_terbaru' => $student->saldo,
            'nama_anak' => $student->nama,
        ]);
    }

    // ================= SIMPAN SCAN RFID =================
    public function saveScan(Request $request)
    {
        $uid = $request->uid;

        $student = Student::where('uid_rfid', $uid)->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Kartu tidak dikenal'
            ], 404);
        }

        RfidScan::create([
            'uid_rfid' => $uid,
            'student_id' => $student->id,
            'scanned_at' => now(),
            'card_saldo' => $request->card_saldo,
        ]);

        $foods = Food::all();

        return response()->json([
            'success' => true,
            'student_id' => $student->id,
            'nama' => $student->nama,
            'saldo' => $student->saldo,
            'card_saldo' => $request->card_saldo,
            'foods' => $foods
        ]);
    }

    // ================= AMBIL SCAN TERAKHIR =================
    public function lastScan()
    {
        $scan = RfidScan::with('student')->latest()->first();

        if (!$scan || !$scan->student) {
            return response()->json([
                'success' => false,
                'message' => 'Belum ada scan'
            ]);
        }

        $foods = Food::all();

        return response()->json([
            'success' => true,
            'student_id' => $scan->student_id,
            'nama' => $scan->student->nama,
            'saldo' => $scan->student->saldo,
            'card_saldo' => $scan->card_saldo,
            'scanned_at' => $scan->scanned_at,
            'foods' => $foods
        ]);
    }

    public function tapCard(Request $request)
    {
        $uid = $request->uid;

        $student = Student::where('uid_rfid', $uid)->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Kartu tidak dikenal'
            ], 404);
        }

        RfidScan::create([
            'uid_rfid' => $uid,
            'student_id' => $student->id,
            'scanned_at' => now(),
        ]);

        $foods = Food::all();

        return response()->json([
            'success' => true,
            'student_id' => $student->id,
            'nama' => $student->nama,
            'saldo' => $student->saldo,
            'foods' => $foods
        ]);
    }

    public function buyFromCard(Request $request)
    {
        $uid = $request->uid;
        $food_id = $request->food_id;
        $student_id = $request->student_id;

        $student = null;
        if ($student_id) {
            $student = Student::find($student_id);
        } elseif ($uid) {
            $student = Student::where('uid_rfid', $uid)->first();
        }

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Kartu tidak dikenal'
            ], 404);
        }

        $food = Food::find($food_id);

        if (!$food) {
            return response()->json([
                'success' => false,
                'message' => 'Makanan tidak ditemukan'
            ], 404);
        }

        if ($student->saldo < $food->harga) {
            return response()->json([
                'success' => false,
                'message' => 'Saldo tidak cukup'
            ], 400);
        }

        $student->saldo -= $food->harga;
        $student->save();

        Transaction::create([
            'student_id' => $student->id,
            'nama_makanan' => $food->nama_makanan,
            'harga' => $food->harga,
            'waktu' => now(),
            'foto' => $food->foto,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pembelian berhasil',
            'saldo' => $student->saldo,
            'nama_anak' => $student->nama,
            'nama_makanan' => $food->nama_makanan
        ]);
    }

    public function history()
    {
        $transactions = Transaction::latest()->get();

        return response()->json([
            'success' => true,
            'data' => $transactions
        ]);
    }

    public function monthlyTotal($studentId, Request $request)
    {
        $month = $request->query('month');
        $query = Transaction::where('student_id', $studentId);
        if ($month) {
            $year = substr($month, 0, 4);
            $m = substr($month, 5, 2);
            $query->whereYear('waktu', $year)
                  ->whereMonth('waktu', $m);
        }
        $total = $query->sum('harga');

        return response()->json([
            'success' => true,
            'total' => $total
        ]);
    }

    public function historyByStudent($studentId, Request $request)
    {
        $query = Transaction::where('student_id', $studentId);
        $date = $request->query('date');
        if ($date) {
            $query->whereDate('waktu', $date);
        }
        $transactions = $query->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $transactions
        ]);
    }

    public function store(Request $request)
    {
        Food::create([
            'nama_makanan' => $request->nama_makanan,
            'harga' => $request->harga,
            'komposisi' => $request->komposisi,
            'foto' => $request->foto,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Makanan berhasil ditambahkan'
        ]);
    }

    public function update(Request $request, $id)
    {
        $food = Food::find($id);

        if (!$food) {
            return response()->json([
                'success' => false,
                'message' => 'Food tidak ditemukan'
            ], 404);
        }

        $food->update([
            'nama_makanan' => $request->nama_makanan,
            'harga' => $request->harga,
            'komposisi' => $request->komposisi,
            'foto' => $request->foto,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Makanan berhasil diupdate'
        ]);
    }

    public function destroy($id)
    {
        $food = Food::find($id);

        if (!$food) {
            return response()->json([
                'success' => false,
                'message' => 'Food tidak ditemukan'
            ]);
        }

        $food->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil dihapus'
        ]);
    }
}

