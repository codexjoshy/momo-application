<?php
namespace Database\Seeders\Traits;

use Illuminate\Support\Facades\DB;

    /**
     * truncate table trait
     */
    trait HandleForeignKeys
    {
        public function disableForeignKey()
        {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        }
        public function enableForeignKey()
        {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        }
    }


?>
