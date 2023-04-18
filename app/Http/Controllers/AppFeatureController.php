<?php

namespace App\Http\Controllers;

use AfrikaTalkingService;
use App\Models\AppFeature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreAppFeatureRequest;
use App\Http\Requests\UpdateAppFeatureRequest;
use App\Services\AppFeatureService;
use App\Services\Contracts\SmsServiceInterface;

class AppFeatureController extends Controller
{
    protected SmsServiceInterface $smsService;
    public function __construct(SmsServiceInterface $smsService)
    {
        // $this->middleware('auth');
        $this->smsService = $smsService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!$request->has('type')) {
            return redirect()->route('admin.feature.schedule.index', ['type' => 'airtime']);
        }
        $view = $request->type;
        $FeatureSchedules = AppFeature::where('type', $request->type)
            ->latest()
            ->get();
        $smsBalance = $this->smsService->checkBalance();
        $afrikaT = (new \App\Services\afrikaT\AfrikaTalkingService)->getBalance();
        if ($afrikaT['status'] == 'success') {
            $bal = explode(" ", $afrikaT['data']->UserData->balance);
            $afrikaTBalance = end($bal);
        }
        return view("admin.$view.index", compact('FeatureSchedules', 'smsBalance', 'afrikaTBalance'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if (!$request->has('type')) {
            return redirect()->route('admin.feature.schedule.index', ['type' => 'airtime']);
        }
        $view = $request->type;
        $smsBalance = $this->smsService->checkBalance();
        return view("admin.$view.create", compact('smsBalance'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAppFeatureRequest $request, SmsServiceInterface $smsService, AppFeatureService $FeatureService)
    {
        $k = $amountSum = 0;
        $errors = $phones = $customers = [];
        try {
            $handle = fopen($request->upload, 'r');

            while (($record = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($k != 0) {
                    [$phone, $amount] = $record;
                    $phone = (int) trim($phone);
                    $amount = trim($amount);
                    if ($phone && $amount && $amount > 0) {
                        if (!filter_var($phone, FILTER_VALIDATE_INT)) $errors[$k] = "invalid phone provided,";
                        if (strlen($phone) < 10 || strlen($phone) > 13) $errors[$k] = "phone length " . strlen($phone) . " does not meet standards,";
                        if (in_array($phone, $phones)) $errors[$k] = "duplicate phone number,";
                        $phones[] = "$phone";

                        if (!count($errors)) {
                            $customers[] = ["phoneNumber" => "+" . $phone, "amount" => floatval($amount), "currencyCode" => "NGN"];
                        }
                    } else {
                        $errors[$k] =  "invalid data provided";
                    }

                    $amountSum += floatval($amount);
                }
                $k++;
                throw_if(count($errors), ("Error Processing Request"));
            }
            // dd($phones);
            // $d = $FeatureService->sendSms($phones, "Congratulations you won", $smsService);
            // dd($d);
            DB::beginTransaction();
            $schedule = AppFeature::create([
                "uploaded_by" => auth()->id(),
                "title" => $request->title,
                "message" => $request->message,
                "total" => floatval($request->amount)
            ]);
            // $data = [];
            // foreach ($customers as $customer) {
            //     $data[] = ["momo_schedule_id"=> $schedule->id, ...$customer];
            // }
            // DB::table('app_feature_customers')->insert($data);
            $response = $FeatureService->sendAirTime($schedule->id, $customers);
            if ($response['status']) {
                $sms = $FeatureService->sendSms($response['sentPhones'], $schedule->message, $smsService);
                DB::table('app_feature_customers')->insert($response['customers']);
                $schedule->update(["sms_id" => $sms['messageId'] ?? '']); #102023040820320800000064662
            } else {
                throw new \Exception($response['error'] ?? 'sorry something went wrong sending airtime');
            }
            DB::commit();
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
        return redirect()->route('admin.feature.schedule.index', ['type' => 'airtime'])->with('success', "successful");
    }

    /**
     * Display the specified resource.
     */
    public function show(AppFeature $appFeature)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AppFeature $appFeature)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAppFeatureRequest $request, AppFeature $appFeature)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AppFeature $appFeature)
    {
        //
    }
}
