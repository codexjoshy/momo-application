<?php
namespace App\Services\afrikaT;
use Exception;

use AfricasTalking\SDK\AfricasTalking;

  class AfrikaTalkingService
  {
    private $apikey;
    private $username;
    private $client;
    private $from;

    public function __construct() {
        try {
            $this->apikey = config('afrika-talking.apiKey');
            $this->username = config('afrika-talking.username');
            $this->from = config('afrika-talking.smsFrom');
            $this->client = new AfricasTalking($this->username, $this->apikey) ;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * used to send airtime to phoneNumbers
     *
     * @param array $recipients
     * @return array
     */
    public function sendAirtime(array $recipients, string $message=""):array
    {
        foreach ($recipients as $recipient) {
            if(!is_array($recipient)) throw new Exception("Invalid recipient provided");
            if(!array_key_exists('phoneNumber', $recipient) || !array_key_exists('currencyCode', $recipient) ||
            !array_key_exists('amount', $recipient) ) throw new Exception("valid keys are phoneNumber,currencyCode and amount ", 1);
            if($recipient['amount'] && !(int) $recipient['amount']) throw new Exception("invalid amount {$recipient['amount']}", 1);
            $phoneNumbers[] = $recipient['phoneNumber'];

        }
        // $this->sendSMS("This is a test message", $phoneNumbers);
        $Airtime = $this->client->airtime();
        $result = $Airtime->send(["recipients"=>$recipients], ["maxNumRetry"=>3]);
        $status  = $result['status'] ?? '';
        $numberSent = $result['data']->numSent ?? 0;
        $errorMessage = $result['data']->errorMessage ?? null;
        if($result && (strtolower($status) =="success") && ($numberSent > 0)){
            $responses = $result['data']->responses;
            $phoneNumbers = array_column($responses, 'phoneNumber');
            $this->sendSMS($message, $phoneNumbers);
            return ['status'=> true, 'data'=>$result['data']->responses];
        }
        if($errorMessage && strtolower($errorMessage) != "none")throw new Exception("Sorry an error occurred: $errorMessage", 1);
        return  ['status'=> false, 'data'=>$result['data']];
    }

    public function sendSMS(string $message, array $recipients)
    {
        $Sms = $this->client->sms();
        $result = $Sms->send([
            "message"=> $message,
            "to"=>$recipients,
            "from"=> $this->from,
            "enqueue"=>true
        ]);
        if ($result && strtolower($result['status']) == 'success') return true;
        return false;
    }

    /**
     * used to get application details
     *
     * @return array
     */
    public function getBalance():array
    {
        $application = $this->client->application();
        $details = $application->fetchApplicationData();
        return $details;
    }
  }



//   array:2 [▼ // app/Services/afrikaT/AfrikaTalkingService.php:40
//   "status" => "success"
//   "data" => {#1420 ▼
//     +"errorMessage": "Invalid Requests"
//     +"numSent": 0
//     +"totalAmount": "0"
//     +"totalDiscount": "0"
//     +"responses": array:2 [▼
//       0 => {#1418 ▼
//         +"phoneNumber": "+2348140000000"
//         +"errorMessage": "Value Outside The Allowed Limits"
//         +"amount": "NGN 50000.0000"
//         +"status": "Failed"
//         +"requestId": "None"
//         +"discount": "0"
//       }
//       1 => {#1403 ▶}
//     ]
//   }
// ]
