<?php

namespace App\Services;

use App\Models\AppFeature;
use Illuminate\Support\Facades\DB;
use App\Services\afrikaT\AfrikaTalkingService;

class AppFeatureService
{
    protected AfrikaTalkingService $afrikaTalkingService;

    public function sendAirTime(int $schedule, array $recipients)
    {
        try {
            $scheduleInfo = AppFeature::findOrFail($schedule);
            $sent = (new AfrikaTalkingService)->sendAirtime($recipients);
            if ($sent['status']) {
                $customers = [];
                foreach ($sent['data'] as $customer) {
                    $status = 'pending';
                    if(strtolower($customer->status) == 'sent') $status = 'success';
                    if(strtolower($customer->status) == 'failed') $status = 'fail';
                    $amount = explode(" ",$customer->amount);
                    $customers[] = [
                        "app_feature_id"=> $schedule,
                        "phone_no"=> $customer->phoneNumber,
                        "amount"=> end($amount),
                        "status"=> $status,
                        "transaction_id"=> $customer->requestId,
                        "other_info"=> json_encode($customer)
                    ];
                }
                DB::table('app_feature_customers')->insert($customers);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        ;
    }
}
