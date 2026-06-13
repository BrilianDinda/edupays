## Hardware Tambahan (Opsional - untuk tombol seleksi makanan)

Tambah 5 tombol untuk pilih makanan (butuh resistor 10k Ohm pull-up masing-masing):

| Tombol | ESP-32 |
|--------|--------|
| Tombol 1 | GPIO 13 |
| Tombol 2 | GPIO 14 |
| Tombol 3 | GPIO 15 |
| Tombol 4 | GPIO 16 |
| Tombol 5 | GPIO 17 |

Koneksi tombol:
- satu kaki tombol ke GPIO (di atas)
- kaki lain tombol ke GND
- gunakan pinMode INPUT_PULLUP di kode (tidak perlu resistor eksternal)

## Langkah 2: Setup Arduino IDE

1. Download Arduino IDE (arduino.cc)
2. Tambah Board Manager URL: File > Preferences > Additional Boards Manager URLs:
   ```
   https://raw.githubusercontent.com/espressif/arduino-esp32/gh-pages/package_esp32_index.json
   ```
3. Install board: Tools > Board > Boards Manager > ESP32
4. Pilih board: Tools > Board > ESP32 Dev Module

## Langkah 3: Install Library

5. Buka Tools > Manage Libraries
6. Cari dan install:
   - MFRC522 (by GithubCommunity)
   - ArduinoJson (by Benoit Blanchon)

## Langkah 4: Edit skrip esp32_rfid.ino

Ganti baris ini di skrip:
```cpp
const char* ssid = "nama_wifi";
const char* password = "password_wifi";
const char* apiUrl = "http://192.168.1.10/edupays/public/api";
```

- `ssid` dan `password`: WiFi di rumah sekolah
- `apiUrl`: IP server Laravel (cari via `ipconfig` di cmd)

## Langkah 5: Register Kartu RFID

1. Scan kartu RFID dengan ESP-32 (buka Serial Monitor 115200 baud)
2. Catat UID yang muncul (contoh: `5a9f2c8d`)
3. Masuk ke database, tabel `students`
4. Isi kolom `uid_rfid` dengan UID tersebut

## Langkah 6: Upload & Test

6. hubungkan ESP-32 via USB
7. Pilih port yang benar di Tools > Port
8. Upload kode (tombol upload)
9. Buka Serial Monitor (115200 baud)
10. Tap kartu ke RFID-RC522
11. Lihat hasil di Serial Monitor

## Troubleshooting

- Kartu tidak terdeteksi: Cek koneksi SDA-RST
- WiFi gagal konek: Pastikan IP server benar dan bisa diakses
- API error: Cek route `/tap-card` dan `/buy-food` sudah ada