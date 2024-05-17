<?php

namespace App\Console\Commands;

use App\Services\airtime\AirtimeProcessor;
use Illuminate\Console\Command;

class TestAppCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:app';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $operatorCodeList = config('airtime.operatorCodeList');
        dd($operatorCodeList);
        $phone = "2348135978939";
        $last4Digit = "0" . substr($phone, 3, 3);
        $operator = (new AirtimeProcessor)->detectOperator($last4Digit);
        dd($operator, $last4Digit);
    }
}
