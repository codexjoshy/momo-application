<?php

namespace App\Services\airtime;

use App\Models\Uuid;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class AirtimeProcessor
{
 // http://64.226.97.232:27171/jmg/web.html?jParams=eyJvZ24iOiIwODEyNjcwNjMzMyIsIm FwaWQiOiJlbW1hIiwiYW10IjoiMTAwIiwiYWsiOiJlODJjYjU2ZDMzNjhkMDY5MzA2ZjdjM WQ3ODBlZTljYyIsImNvbmZpZyI6ImFwaSIsIm1rIjpmYWxzZSwic2lkIjoiSXJlZGUtMS0yM DIyLTAxLTA0In0=
 /**
  * The http handler.
  */
 protected $httpResponse;

 private $apikey;
 private $username;
 private $client;
 private $from;
 private $baseUrl;
 public function __construct()
 {
  try {
   $this->apikey = config('airtime.apiKey');
   $this->username = config('airtime.username');
   $this->from = config('airtime.smsFrom');
   $this->baseUrl = config('airtime.base_url');
   $this->client =  new Client();
  } catch (\Throwable $th) {
   throw $th;
  }
 }
 public function sendAirtime(string $phone, float $amount, ?string $message, ?string $from = null): string|array
 {
  //   {"ogn":"08055555555","opid":"012","amt":"10000","mk":false,"sid":"Ivvvv-1-2022-01-04", "config":"api", "ak":"e82cb56d3368d069306f7c1d780ee", "apid":"abc-bet" }'
  // Or
  // '{"ogn":"08055555555","amt":"25","mk":false,"sid":"wIrede-1-2022-01-04", "config":"api", "ak":"e82cb56d3368d069306f7c1d780e", "apid":"abc-bet","data":true, "did":"18" }' data is true for data crediting and false for airtime crediting

  // {"ogn":"08055555555","opid":"012","amt":"10000","mk":false,"sid":"Ivvvv-1-2022-01-04", "config":"api", "ak":"e82cb56d3368d069306f7c1d780ee", "apid":"abc-bet" }'

  //{"sid":"TX84af8041-7599-4f6b-befb-35507d205753","config":"api","ak":"7dd5dccf8012c6412b079cb32660b981","apid":"JollyTetra","ogn":"+2348135978939","opid":"011","amt":"5","mk":false,"data":false}  
  $phone = trim($phone);
  $amount = trim($amount);
  $environment = strtolower(app()->environment());
  $operatorCode = $this->getOperatorCode($phone);
  $phone = "0" . ltrim($phone, "+234");
  $data = ["ogn" => $phone, "opid" => $operatorCode, "amt" => $amount, "mk" => $environment != 'production', "data" => false];
  $this->logger("generated data request payload", $data);
  $tranxId = $this->generateUUid();
  $encodedData = $this->generateData($data, $tranxId, true);

  $this->httpResponse = Http::get($this->baseUrl, ["jParams" => $encodedData]);
  $this->logger("Response of airtime sending to $phone", json_encode($this->getResponse()));

  return $this->getResponse();
 }
 public function checkBalance2(): string|array
 {
  $time = time();
  $encodedData = $this->generateData([], "BAL-$time");
  $balanceUrl = "{$this->baseUrl}";

  $client = new Client();
  $balance = 0;
  try {
   // $this->httpResponse  = Http::get("https://jsonplaceholder.typicode.com/todos/1");
   // $response = $this->getResponse();
   // $this->logger("balance check", json_encode($response));
   $data = json_encode(["sid" => "iIvvvv-1-2022-01-04", "config" => "api", "ak" => "7dd5dccf8012c6412b079cb32660b981", "apid" => "JollyTetra", "rc" => "1", "type" => "7"]);
   $response = $client->request('GET', $balanceUrl, [
    'query' => ['jParams' => urlencode(base64_encode($data))]
   ]);

   $responseData = $this->getResponseData($response);
   $this->logger("balance check", json_encode($responseData));

   if ($responseData['status']) {
    $data = $responseData['data'];
    $balance = $data['balances']['main'] ?? 0;
    return ["balance" => $balance];
   } else {
    return ["balance" => $balance];
   }
  } catch (ClientException $e) {
   Log::error('API request failed: ' . $e->getMessage());
   return ["balance" => $balance];
  } catch (\Exception $e) {
   Log::error('API request failed: ' . $e->getMessage());
   return ["balance" => $balance];
  }
 }
 public function checkBalance(): string|array
 {
  $encodedData = $this->generateData();
  $balanceUrl = "{$this->baseUrl}";
  $this->httpResponse = Http::get($balanceUrl, ["jParams" => $encodedData]);
  $response = $this->getResponse();
  $this->logger("balance check", json_encode($response));
  $balance = 0;
  if ($response['status']) {
   $data = $response['data'];
   $balance = $data['balances']['main'] ?? 0;
  }
  return ["balance" => $balance];
 }
 /**
  * used generate encoded data to be sent to api endpoint
  */
 private function generateData(array $extraData = [], string $transaxId = "BAL", $isAirtime = false)
 {
  // '{"sid":"iIvvvv-1-2022-01-04", "config":"api", "ak":"e82cb56d3368d069306f7c1d780ee9cc", "apid":"better","rc":"1", "type":"7"}'
  $data = $isAirtime ?  ["sid" => $transaxId, "config" => "api", "ak" => $this->apikey, "apid" => $this->username] : ["sid" => $transaxId, "config" => "api", "ak" => $this->apikey, "apid" => $this->username, "rc" => "1", "type" => "7"];
  $data = count(array_keys($extraData)) ? array_merge($data, $extraData) : $data;

  $this->logger("generated data AIRTIME: $isAirtime ", json_encode($data));
  $data = base64_encode(json_encode($data));
  return $data;
 }
 private function generateUUid(): string
 {
  $uuid = "";
  do {
   $uuid = "TX" . Str::uuid()->toString();
  } while ($uuid && Uuid::where("uuid", $uuid)->exists());

  Uuid::create(['uuid' => $uuid]);

  return $uuid;
 }

 public  function getOperatorCode($phoneNumber): string|null
 {
  $operatorCodes = config('airtime.operators');
  $phoneNumber = ltrim($phoneNumber, "+");
  $last4Digit = "0" . substr($phoneNumber, 3, 3);
  $operator = $this->detectOperator($last4Digit);
  $operatorCode = $operatorCodes[$operator] ?? null;
  return $operatorCode;
 }

 public function detectOperator($firstFourDigit): string
 {
  $operatorCodeList = config('airtime.operatorCodeList');

  return $operatorCodeList[$firstFourDigit];
 }


 private function getResponseData($response)
 {
  $statusCode = $response->getStatusCode();
  $responseData = $response->getBody()->getContents();

  if ($statusCode === 200) {
   return [
    "status" => true,
    "data" => json_decode($responseData, true),
    "message" => $response->getReasonPhrase(),
    "statusCode" => $statusCode
   ];
  } else {
   return [
    "status" => false,
    "data" => [],
    "message" => $response->getReasonPhrase(),
    "statusCode" => $statusCode
   ];
  }
 }
 /**
  * Undocumented function
  *
  *
  */
 private function getResponse()
 {
  if ($this->httpResponse) {
   // dd($this->httpResponse);
   if ($this->httpResponse->failed()) {
    return [
     "status" => false, "data" => [],
     "message" => $this->httpResponse->getReasonPhrase(),
     "statusCode" => $this->httpResponse->status()
    ];
   }
   return [
    "status" => true, "data" => $this->httpResponse->json(),
    "message" => $this->httpResponse->getReasonPhrase(),
    "statusCode" => $this->httpResponse->status()
   ];
  }
  return [
   "status" => false, "data" => [], "message" => "no response found",  "statusCode" => 500
  ];
 }

 private function logger($title, $data)
 {

  Log::channel('daily')->info($title);
  Log::channel('daily')->info($data);
 }
}
