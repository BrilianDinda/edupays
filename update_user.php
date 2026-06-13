<?php
require __DIR__.'/bootstrap/app.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$user = App\Models\User::where('username', 'admin')->first();
$user->username = 'admin';
$user->save();
echo 'User updated with username: ' . $user->username . PHP_EOL;
