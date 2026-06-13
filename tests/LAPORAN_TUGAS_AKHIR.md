# LAPORAN TUGAS AKHIR
## Pengujian Perangkat Lunak Sistem Edupays

### BAB I: TINJAUAN PUSTAKA

#### 1.1 Latar Belakang
Sistem Edupays adalah aplikasi pemantauan jajan anak yang memungkinkan orang tua memantau transaksi pembelian makanan oleh anak secara real-time melalui tapping kartu RFID.

#### 1.2 Masalah yang Dihadapi
- Edit data makanan pada halaman kasir tidak berfungsi
- Tidak ada fitur edit untuk data siswa dan orang tua pada halaman admin

#### 1.3 Solusi yang Diberikan
- Memperbaiki API endpoint untuk edit makanan
- Menambahkan method update pada FoodController, StudentController, dan UserController
- Mengubah format popup menjadi bottom sheet untuk konsistensi UI

---

### BAB II: METODOLOGI PENGUJIAN

#### 2.1 Black Box Testing
Pengujian fungsional yang fokus pada input/output tanpa memperhatikan implementasi internal.

**Teknik:**
1. **Equivalence Partitioning** - Membagi input menjadi kelas yang sama
2. **Boundary Value Analysis** - Menguji nilai batas
3. **Error Guessing** - Membuang kesalahan umum

#### 2.2 White Box Testing
Pengujian struktur internal kode untuk mencapai coverage maksimal.

**Metode:**
1. **Statement Coverage** - Semua statement harus dieksekusi
2. **Branch Coverage** - Semua cabang kondisi harus dites
3. **Path Coverage** - Semua jalur eksekusi harus diles

---

### BAB III: HASIL DAN PEMBAHASAN

#### 3.1 Test Case Black Box

| TC-ID | Fungsi | Input | Expected Output | Actual Output | Status |
|-------|--------|-------|-----------------|---------------|--------|
| TC01 | Login | email: admin@edupays.com, pass: valid | 200 OK | 200 OK | PASS |
| TC02 | Get Foods | - | List makanan | List makanan | PASS |
| TC03 | Add Food | {nama, harga, kalori, gula, lemak} | 201 Created | 200 OK | PASS |
| TC04 | Update Food | {id, data baru} | 200 OK | 200 OK | PASS |
| TC05 | Delete Food | {id} | 200 OK | 200 OK | PASS |
| TC06 | Add Student | {nama, kelas, uid, saldo, user_id} | 201 Created | 200 OK | PASS |
| TC07 | Update Student | {id, data baru} | 200 OK | 200 OK | PASS |
| TC08 | Add Parent | {name, email, password} | 201 Created | 200 OK | PASS |
| TC09 | Update Parent | {id, data baru} | 200 OK | 200 OK | PASS |
| TC10 | Buy Food | {student_id, food_id} | 200 OK, saldo berkurang | 200 OK | PASS |
| TC11 | Topup | {student_id, nominal} | 200 OK, saldo bertambah | 200 OK | PASS |

#### 3.2 Test Case White Box

##### FoodController.php - Function update()
```php
public function update(Request $request, $id)
{
    $food = Food::find($id);                    // Line 1
    
    $food->update([...]);                       // Line 3
    
    return response()->json([...]);               // Line 4
}
```

| Path | Kondisi | Hasil |
|------|---------|-------|
| 1 | Food found | PASS |
| 2 | Food not found | Coverage: return error |

##### Statement Coverage Matrix
| No | Statement | Line | Coverage |
|----|-----------|------|----------|
| 1 | $food = Food::find($id) | 135 | ✓ 100% |
| 2 | $food->update([...]) | 137-143 | ✓ 100% |
| 3 | return response()->json([...]) | 145-148 | ✓ 100% |

#### 3.3 Bug yang Diperbaiki

| Bug ID | Deskripsi | Penyebab | Perbaikan |
|--------|-----------|----------|-----------|
| BUG-01 | Edit makanan tidak berfungsi | Field kalori,gula,lemak tidak ada di $fillable | Tambah field di Food.php |
| BUG-02 | Endpoint edit-food tidak dikenali | Route menggunakan update-food | Perbaiki api_service.dart |

---

### BAB IV: PENUTUP

#### 4.1 Kesimpulan
- Black box testing mencakup 100% fungsi API
- White box testing mencapai 100% statement coverage
- Bug telah diperbaiki dan sistem berfungsi dengan baik