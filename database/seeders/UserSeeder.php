<?php

namespace Database\Seeders;

use Database\Seeders\Traits\HandleForeignKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
