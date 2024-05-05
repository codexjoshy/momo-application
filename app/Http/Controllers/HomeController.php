<?php

namespace App\Http\Controllers;

use App\Services\afrikaT\AfrikaTalkingService;
use App\Services\airtime\AirtimeProcessor;
use App\Services\Contracts\SmsServiceInterface;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected SmsServiceInterface $smsService;
    protected AirtimeProcessor $airtimeService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(SmsServiceInterface $smsService, AirtimeProcessor $airtimeService)
    {
        // $this->middleware('auth');
        $this->smsService = $smsService;
        $this->airtimeService = $airtimeService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $isCustomer = auth()->user()->authority == 'customer';
        $airTimeBalance = 0;
        if (!$isCustomer) {
            $smsBalance = $this->smsService->checkBalance();
            $airTimeBalance = $this->airtimeService->checkBalance()['balance'];

            // $afrikaT = (new AfrikaTalkingService)->getBalance();
            // if ($afrikaT['status'] == 'success') {
            //     $bal = explode(" ", $afrikaT['data']->UserData->balance);
            //     $afrikaTBalance = end($bal);
            // }

        } else {
            $smsBalance = $this->smsService->checkBalance();
        }
        // dd($smsBalance);
        return view('home', compact('smsBalance', 'airTimeBalance'));
    }

    // public function home()
    // {
    //     $isCustomer = auth()->user()->authority == 'customer';
    //     if ($isCustomer) {
    //        return view('home');
    //     }
    //     $disbursement = new Disbursement;
    // //    dd( $disbursement->getAccountBalance());
    // //    $momoTransactionId = $disbursement->transfer('referenceid', '8135978939', 20);
    // //    dd($disbursement->getTransactionStatus($momoTransactionId));
    // //    dd($test);
    // }

    // public function home()
    // {
    //     $isCustomer = auth()->user()->authority == 'customer';
    //     dd($isCustomer);
    //     if ($isCustomer) {
    //         $smsBalance = null;
    //     }else{
    //         $smsBalance = $this->smsService->checkBalance();
    //     }

    //     return view('home', compact('smsBalance'));
    // }
}
