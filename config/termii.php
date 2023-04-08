<?php
  return [
   'apiKey' => env('SMS_APIKEY'),
//    'baseUrl'=> 'https://termii.com/api',
//    'baseUrl'=> 'https://api.ng.termii.com/api/get-balance',
   'baseUrl'=> 'https://api.ng.termii.com/api/',
   'from'=> env('SMS_FROM'),
   'channel'=> env('SMS_CHANNEL'),
  ];

?>
