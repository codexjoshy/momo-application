<?php

namespace App\Http\Controllers;

use AfrikaTalkingService;
use App\Models\AppFeature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreAppFeatureRequest;
use App\Services\airtime\AirtimeProcessor;
use App\Services\Contracts\SmsServiceInterface;

class AppFeatureController extends Controller
{
    protected SmsServiceInterface $smsService;
    protected AirtimeProcessor $airtimeService;
    public function __construct(SmsServiceInterface $smsService, AirtimeProcessor $airtimeService)
    {
        // $this->middleware('auth');
        $this->smsService = $smsService;
        $this->airtimeService = $airtimeService;
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
        // $afrikaT = (new \App\Services\afrikaT\AfrikaTalkingService)->getBalance();
        $airtimeBal = $this->airtimeService->checkBalance()['balance'];
        return view("admin.$view.index", compact('FeatureSchedules', 'smsBalance', 'airtimeBal'));
    }
    public function view(AppFeature $feature)
    {
        $customers = $feature->customers;
        return view("admin.airtime.view", compact('customers', 'feature'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if (!$request->has('type') || $request->type != 'airtime') {
            return redirect()->route('admin.feature.schedule.index', ['type' => 'airtime']);
        }
        $view = $request->type;
        $smsBalance = $this->smsService->checkBalance();
        $airTimeBalance = $this->airtimeService->checkBalance();
        return view("admin.$view.create", compact('smsBalance'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAppFeatureRequest $request)
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
            $data = [];
            foreach ($customers as $customer) {
                $data[] = [
                    "app_feature_id" => $schedule->id,
                    "phone_no" => ltrim($customer['phoneNumber'], "+"),
                    "amount" => $customer['amount'],
                    "status" => "pending",
                    "created_at" => now(),
                    "updated_at" => now(),
                ];
            }
            DB::table('app_feature_customers')->insert($data);
            // $response = $FeatureService->sendAirTime($schedule->id, $customers);
            // if ($response['status']) {
            //     $sms = $FeatureService->sendSms($response['sentPhones'], $schedule->message, $smsService);
            //     DB::table('app_feature_customers')->insert($response['customers']);
            //     $schedule->update(["sms_id" => $sms['messageId'] ?? '']); #102023040820320800000064662
            // } else {
            //     throw new \Exception($response['error'] ?? 'sorry something went wrong sending airtime');
            // }
            DB::commit();
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
        return redirect()->route('admin.feature.schedule.index', ['type' => 'airtime'])->with('success', "Schedule has been uploaded, you can track your schedule");
    }
}
