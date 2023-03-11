<?php

namespace Database\Seeders;

use App\Models\MomoSchedule;
use Illuminate\Database\Seeder;
use Database\Seeders\Traits\TruncateTable;
use Database\Seeders\Traits\HandleForeignKeys;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MomoScheduleSeeder extends Seeder
{
    use TruncateTable, HandleForeignKeys;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->disableForeignKey();
        $this->truncate('momo_schedules');
        \App\Models\MomoSchedule::factory(5)->create();
        $this->enableForeignKey();
    }
}
