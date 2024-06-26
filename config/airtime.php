<?php
return [
  'base_url' => env('AIRTIME_BASE_URL', 'http://64.226.97.232:27171/jmg/web.html'),
  'username' => env('AIRTIME_USERNAME'),
  'apiKey' => env('AIRTIME_API_KEY'),
  'smsFrom' => env('AIRTIME_SMS_FROM'),
  'operators' => [
    "glo" => '010',
    "mtn" => '011',
    "airtel" => '012',
    "9mobile" => '013',
  ],
  'operatorCodeList' => [
    '0802' => 'airtel',
    '0808' => 'airtel',
    '0708' => 'airtel',
    '0812' => 'airtel',
    '0902' => 'airtel',
    '0907' => 'airtel',
    '0803' => 'mtn',
    '0806' => 'mtn',
    '0703' => 'mtn',
    '0706' => 'mtn',
    '0813' => 'mtn',
    '0816' => 'mtn',
    '0810' => 'mtn',
    '0814' => 'mtn',
    '0903' => 'mtn',
    '0906' => 'mtn',
    '0805' => 'glo',
    '0807' => 'glo',
    '0705' => 'glo',
    '0811' => 'glo',
    '0815' => 'glo',
    '0905' => 'glo',
    '0809' => '9mobile',
    '0817' => '9mobile',
    '0818' => '9mobile',
    '0909' => '9mobile',
    '0908' => '9mobile',
  ]
];
