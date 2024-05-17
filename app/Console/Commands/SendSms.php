<?php

namespace App\Console\Commands;

use App\Models\AppFeature;
use App\Services\AppFeatureService;
use App\Services\Termi\TermiiSmsService;
use Illuminate\Console\Command;

class SendSms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:sms';

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
        $FeatureService = new AppFeatureService;
        $smsService = new TermiiSmsService;
        $schedule = AppFeature::with('customers')->whereNull('sms_id')->whereHas('customers', fn ($q) => $q->where('status', 'success'))->first();
        if ($schedule) {
            $customers = $schedule->customers->where('status', 'success');
            $total = $customers->sum('amount');
            $phones = $customers->pluck('phone_no')->toArray();
            if ($customers && (float)$total == (float)$schedule->total) {
                $sms = $FeatureService->sendSms($phones, $schedule->message, $smsService);
                $schedule->update(["sms_id" => $sms['messageId'] ?? '', "other_info" => $sms['data']]);
            }
        }
    }
}
