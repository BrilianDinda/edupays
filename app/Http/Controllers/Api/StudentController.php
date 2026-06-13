<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    // ================= GET ALL =================
    public function index(Request $request)
    {
        $query = Student::with('user');
        
        if ($request->has('search') && $request->search != '') {
            $query->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('kelas', 'like', '%' . $request->search . '%');
        }
        
        return response()->json([
            'success' => true,
            'data' => $query->get()
        ]);
    }

    // ================= ADD =================
    public function store(Request $request)
    {
        try {

            $request->validate([
                'nama' => 'required',
                'kelas' => 'required',
                'uid_rfid' => 'required',
                'saldo' => 'required',
                'user_id' => 'required',
            ]);

            Student::create([
                'nama' => $request->nama,
                'kelas' => $request->kelas,
                'uid_rfid' => $request->uid_rfid,
                'saldo' => $request->saldo,
                'user_id' => $request->user_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Student berhasil ditambah'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // ================= DELETE =================
    public function destroy($id)
    {
        Student::find($id)?->delete();

        return response()->json([
            'success' => true,
            'message' => 'Student berhasil dihapus'
        ]);
    }

    // ================= UPDATE =================
    public function update(Request $request, $id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student tidak ditemukan'
            ], 404);
        }

        $student->update([
            'nama' => $request->nama ?? $student->nama,
            'kelas' => $request->kelas ?? $student->kelas,
            'uid_rfid' => $request->uid_rfid ?? $student->uid_rfid,
            'saldo' => $request->saldo ?? $student->saldo,
            'user_id' => $request->user_id ?? $student->user_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Student berhasil diupdate',
            'data' => $student
        ]);
    }

    // ================= MY STUDENTS =================
    public function myStudents($userId)
    {
        $students = Student::where(
            'user_id',
            $userId
        )->get();

        return response()->json([
            'success' => true,
            'data' => $students
        ]);
    }
}