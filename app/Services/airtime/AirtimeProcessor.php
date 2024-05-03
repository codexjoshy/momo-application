<?php

namespace App\Services\airtime;

use App\Models\Uuid;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
  } catch (\Throwable $th) {
   throw $th;
  }
 }
 public function sendAirtime(string $phone, float $amount, ?string $message, ?string $from = null): string|array
 {
  //   {"ogn":"08055555555","opid":"012","amt":"10000","mk":false,"sid":"Ivvvv-1-2022-01-04", "config":"api", "ak":"e82cb56d3368d069306f7c1d780ee", "apid":"abc-bet" }'
  // Or
  // '{"ogn":"08055555555","amt":"25","mk":false,"sid":"wIrede-1-2022-01-04", "config":"api", "ak":"e82cb56d3368d069306f7c1d780e", "apid":"abc-bet","data":true, "did":"18" }' data is true for data crediting and false for airtime crediting
  $environment = strtolower(app()->environment());
  $operatorCode = "012"; // glo
  $data = ["ogn" => $phone, "opid" => $operatorCode, "amt" => $amount, "mk" => $environment != 'production', "data" => false];
  $tranxId = $this->generateUUid();
  $encodedData = $this->generateData($data, $tranxId);

  $this->httpResponse = Http::get($this->baseUrl, ["jParams" => $encodedData]);
  $this->logger("Response of airtime sending to $phone", json_encode($this->getResponse()));

  return $this->getResponse();
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
 private function generateData(array $extraData = [], string $transaxId = "BAL")
 {
  // '{"sid":"iIvvvv-1-2022-01-04", "config":"api", "ak":"e82cb56d3368d069306f7c1d780ee9cc", "apid":"better","rc":"1", "type":"7"}'
  $data = ["sid" => $transaxId, "config" => "api", "ak" => $this->apikey, "apid" => $this->username, "rc" => "1", "type" => "7"];
  $data = count(array_keys($extraData)) ? array_merge($data, $extraData) : $data;
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