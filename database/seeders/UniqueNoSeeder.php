<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\Traits\TruncateTable;
use Database\Seeders\Traits\HandleForeignKeys;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UniqueNoSeeder extends Seeder
{
    use TruncateTable, HandleForeignKeys;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->disableForeignKey();
        $this->truncate('unique_nos');
        \App\Models\UniqueNo::factory(5)->create();
        $this->enableForeignKey();
    }
}
