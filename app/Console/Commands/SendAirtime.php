<?php

namespace App\Console\Commands;

use App\Models\AppFeatureCustomer;
use App\Services\airtime\AirtimeProcessor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendAirtime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:airtime';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Used to send airtime to a customers ';

    /**
     * Execute the console command.
     */
    public function handle()
    {


        $customers = AppFeatureCustomer::whereNull('transaction_id')->limit(50)->get();
        $AirtimeService = new AirtimeProcessor;
        if ($customers && count($customers) > 0) {
            Log::channel('daily')->info("Sending airtime:...");
            foreach ($customers as $customer) {
                try {
                    $phone = $customer['phone_no'];
                    $amount = floatval($customer['amount']);
                    $response = $AirtimeService->sendAirtime($phone, $amount, "");
                    if ($response['status'] && $response['data']) {
                        $data = $response['data'];
                        $transactionId = $data['sid'];

                        // update customer
                        $customer->update(["transaction_id" => $transactionId, "updated_at" => now(), "status" => 'success', "other_info" => $response]);
                    }
                } catch (\Throwable $th) {
                    //throw $th;
                    $d = json_encode($customer, true);
                    Log::channel('daily')->info("Error processing customer : $d: " . $th->getMessage());
                    $customer->update(["status" => "fail", "other_info" => $th->getMessage()]);
                }
            }

            Log::channel('daily')->info("Airtime sent to customers");
        }
    }
}
