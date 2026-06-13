<?php
require __DIR__.'/bootstrap/app.php';
try {
    Illuminate\Support\Facades\DB::table('students')->first();
    echo 'students table exists';
} catch (Exception $e) {
    echo 'students table does not exist: ' . $e->getMessage();
}
