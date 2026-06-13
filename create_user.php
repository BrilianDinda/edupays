<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Http\Kernel');

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = new User();
$user->name = 'Test User';
$user->email = 'test@example.com';
$user->password = Hash::make('secret');
$user->save();
echo 'User created with ID: ' . $user->id . PHP_EOL;
