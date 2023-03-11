<?php

namespace App\Http\Controllers;

use App\Models\MomoSchedule;
use Illuminate\Http\Request;
use Bmatovu\MtnMomo\Products\Disbursement;
use Bmatovu\MtnMomo\Traits\TokenUtilTrait;

class HomeController extends Controller
{
    use TokenUtilTrait;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function home()
    {
        $disbursement = new Disbursement;
    //    dd( $disbursement->getAccountBalance());
       $momoTransactionId = $disbursement->transfer('referenceid', '8135978939', 20);
       dd($disbursement->getTransactionStatus($momoTransactionId));
    //    dd($test);
    }

}
