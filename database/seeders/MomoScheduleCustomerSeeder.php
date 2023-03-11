<?php

namespace Database\Seeders;

use Database\Seeders\Traits\HandleForeignKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MomoScheduleCustomerSeeder extends Seeder
{
    use TruncateTable, HandleForeignKeys;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->disableForeignKey();
        $this->truncate('momo_schedule_customers');
        \App\Models\MomoScheduleCustomer::factory(10)->create();
        $this->enableForeignKey();
    }
}
