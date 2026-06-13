<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Food;
use App\Models\User;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FoodApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_get_foods_returns_success()
    {
        $response = $this->getJson('/api/foods');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data'
                 ]);
    }

    /** @test */
    public function test_add_food_success()
    {
        $response = $this->postJson('/api/add-food', [
            'nama_makanan' => 'Nasi Goreng',
            'harga' => 15000,
            'kalori' => 300,
            'gula' => 5,
            'lemak' => 10
        ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true, 'message' => 'Makanan berhasil ditambahkan']);

        $this->assertDatabaseHas('foods', [
            'nama_makanan' => 'Nasi Goreng',
            'harga' => 15000
        ]);
    }

    /** @test */
    public function test_update_food_success()
    {
        $food = Food::create([
            'nama_makanan' => 'Mie Ayam',
            'harga' => 12000,
            'kalori' => 250,
            'gula' => 3,
            'lemak' => 8
        ]);

        $response = $this->postJson("/api/update-food/{$food->id}", [
            'nama_makanan' => 'Mie Ayam Bakso',
            'harga' => 15000,
            'kalori' => 350,
            'gula' => 5,
            'lemak' => 12
        ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('foods', [
            'id' => $food->id,
            'nama_makanan' => 'Mie Ayam Bakso',
            'harga' => 15000
        ]);
    }

    /** @test */
    public function test_delete_food_success()
    {
        $food = Food::create([
            'nama_makanan' => 'Test Food',
            'harga' => 10000
        ]);

        $response = $this->postJson("/api/delete-food/{$food->id}");

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('foods', ['id' => $food->id]);
    }

    /** @test */
    public function test_add_student_success()
    {
        $user = User::create([
            'name' => 'Test Parent',
            'username' => 'parent',
            'password' => bcrypt('password'),
            'role' => 'orang_tua'
        ]);

        $response = $this->postJson('/api/add-student', [
            'nama' => 'Test Student',
            'kelas' => 'XI RPL 1',
            'uid_rfid' => 'TEST001',
            'saldo' => 50000,
            'user_id' => $user->id
        ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('students', [
            'nama' => 'Test Student',
            'kelas' => 'XI RPL 1'
        ]);
    }

    /** @test */
    public function test_update_student_success()
    {
        $user = User::create([
            'name' => 'Test Parent',
            'username' => 'parent2',
            'password' => bcrypt('password'),
            'role' => 'orang_tua'
        ]);

        $student = Student::create([
            'nama' => 'Original Name',
            'kelas' => 'XII RPL 2',
            'uid_rfid' => 'TEST002',
            'saldo' => 30000,
            'user_id' => $user->id
        ]);

        $response = $this->postJson("/api/update-student/{$student->id}", [
            'nama' => 'Updated Name',
            'kelas' => 'XII RPL 3',
            'uid_rfid' => 'TEST002',
            'saldo' => 40000,
            'user_id' => $user->id
        ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'nama' => 'Updated Name'
        ]);
    }
}