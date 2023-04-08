<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Database\Seeders\Traits\TruncateTable;
use Database\Seeders\Traits\HandleForeignKeys;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    use TruncateTable, HandleForeignKeys;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $this->disableForeignKey();
        $this->truncate('users');
        \App\Models\User::factory(2)->create();
        $this->enableForeignKey();

        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'admin@tetragrammatongroup.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'authority'=> 'admin',
            'remember_token' => Str::random(10),
        ]);
    }
}
