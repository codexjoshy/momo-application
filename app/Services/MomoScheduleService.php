<?php

namespace App\Services;

use App\Models\MomoSchedule;
use App\Services\momo\DisbursementService;

class MomoScheduleService
{
    protected DisbursementService $disbursementService;

    public function __construct(DisbursementService $disbursementService) {
        $this->disbursementService = $disbursementService;
    }

    public function requestToPay(int $momoSchedule, array $recipients)
    {
        try {
            $schedule = MomoSchedule::find($momoSchedule);
            $this->disbursementService->disburseFunds($recipients, $schedule->disbursed_amount, $schedule->customer_message);
        } catch (\Throwable $th) {
            throw $th;
        }
        ;
    }
}
