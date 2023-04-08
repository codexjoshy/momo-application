<?php

namespace App\Services;

use App\Models\AppFeature;
use Illuminate\Support\Facades\DB;
use App\Services\afrikaT\AfrikaTalkingService;
use App\Services\Contracts\SmsServiceInterface;

class AppFeatureService
{
    protected AfrikaTalkingService $afrikaTalkingService;
    public function sendAirTime(int $schedule, array $recipients)
    {
        try {
            // $scheduleInfo = AppFeature::findOrFail($schedule);
            $sent = (new AfrikaTalkingService)->sendAirtime($recipients);
            if ($sent['status']) {
                $customers = $sentPhones = [];
                foreach ($sent['data'] as $customer) {
                    $status = 'pending';
                    if(strtolower($customer->status) == 'sent') $status = 'success';
                    if(strtolower($customer->status) == 'failed') $status = 'fail';
                    $amount = explode(" ",$customer->amount);
                    $sentPhones[] = ltrim($customer->phoneNumber,"+");
                    $customers[] = [
                        "app_feature_id"=> $schedule,
                        "phone_no"=> $customer->phoneNumber,
                        "amount"=> end($amount),
                        "status"=> $status,
                        "transaction_id"=> $customer->requestId,
                        "other_info"=> json_encode($customer)
                    ];
                }
                if ($sentPhones) {
                    // $this->sendSms($sentPhones, $scheduleInfo->message);
                }
                return [
                    "status"=> true,
                    "customers"=> $customers,
                    "sentPhones"=> $sentPhones
                ];
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        return  [
            "status"=> false,
            "error"=> "Unable to carryout action",
            "customers"=> [],
            "sentPhones"=> []
        ];
    }

    /**
     * send sms to multiple users
     *
     * @param array $recipients
     * @return void
     */
    public function sendSms(array $recipients, $message, SmsServiceInterface $smsService)
    {
        return $smsService->sendBulkSms($recipients, $message);
    }
}
