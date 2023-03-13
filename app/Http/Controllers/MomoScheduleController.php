<?php

namespace App\Http\Controllers;

use App\Models\MomoSchedule;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreMomoScheduleRequest;
use App\Http\Requests\UpdateMomoScheduleRequest;
use App\Models\MomoScheduleCustomer;
use App\Services\MomoScheduleService;
use Exception;

class MomoScheduleController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $momoSchedules = MomoSchedule::query()->with('customers')
            ->latest()
            ->get();
        return view('admin.schedule.index', compact('momoSchedules'));
    }

    public function store(StoreMomoScheduleRequest $request, MomoScheduleService $momoScheduleService)
    {
            $k = $amountSum = 0;
            $errors = [];
            $phones = [];
            $customers = [];
            // $path = $request->file('csvfile')->getRealPath();
            // $data = \Excel::load($path)->get();
            try {
                $handle = fopen($request->upload, 'r');

                while (($record = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if ($k != 0) {
                        [$phone, $amount] = $record;
                        $phone = (int) trim($phone);
                        $amount = trim($amount);
                        if ($phone && $amount && $amount > 0) {
                            if(!filter_var($phone, FILTER_VALIDATE_INT)) $errors[$k] = "invalid phone provided,";
                            if(strlen($phone) < 10 || strlen($phone) > 11) $errors[$k] = "phone length ".strlen($phone)." does not meet standards,";
                            if (in_array($phone, $phones)) $errors[$k] = "duplicate phone number,";
                            $phones[] = $phone;

                            if(!count($errors)){
                                $customers[] = ["phone_no"=> $phone, "amount"=> $amount];
                            }
                        } else {
                            $errors[$k] =  "invalid data provided";
                        }

                        $amountSum += floatval($amount);
                    }
                    $k++;
                    throw_if(count($errors),  ("Error Processing Request"));
                }
                $schedule = MomoSchedule::create([
                    "uploaded_by"=>auth()->id(),
                    "title"=> $request->title,
                    "customer_message"=>$request->customer_message,
                    "disbursed_amount"=> $request->amount
                ]);
                $data = [];
                foreach ($customers as $customer) {
                    $data[] = ["momo_schedule_id"=> $schedule->id, ...$customer];
                }
                DB::table('momo_schedule_customers')->insert($data);
                $momoScheduleService->requestToPay($schedule->id, $data);
                return redirect()->route('admin.schedule.index')->with('success', "successful");
            } catch (\Throwable $th) {
                return back()->with('error', $th->getMessage());
            }



    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.schedule.create');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MomoSchedule $momoSchedule)
    {
        $momoSchedule->delete();
        return back()->with('success', 'deleted successfully');

    }
}
