<?php
namespace App\Services\momo;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

    // use Illuminate\Support\Facades\Http;

class DisbursementService {
    protected $client;
    private $base_url;
    private $api_key;
    private $api_secret;
    private $callback_url;

    public function __construct() {
        $this->client = new Client();
        $this->base_url = config('app.momo.base_url');
        $this->api_key = config('app.momo.disbursement_api_key');
        $this->api_secret = config('app.momo.disbursement_api_secret');
        $this->callback_url = config('app.momo.callback_url');
        throw_if(!($this->client && $this->base_url && $this->api_key &&$this->api_secret&&$this->callback_url), "CONFIGURATION KEYS NOT FULLY SET");
    }

    /**
     * DISBURSE TO MULTIPLE PHONE NUMBERS
     *
     * @param array $recipients
     * @param float $amount
     * @param string $message
     * @param string $note
    //  * @return void
     */
    public function disburseFunds(array $recipients, float $amount, string $message="", string $note="" )
    {
        $headers = [
            'Authorization' => 'Bearer ' . $this->api_key,
            'Content-Type' => 'application/json',
        ];

        $body = [
            'amount' => $amount, // Set the amount to disburse
            'currency' => 'NGN', // Set the currency to disburse
            'externalId' => '1234', // Set a unique identifier for the disbursement
            'payee' => [],
            'payerMessage' => $message, // Set a message for the payment
            'payeeNote' => $note, // Set a note for the payment
        ];

        foreach ($recipients as $recipient) {
            $body['payee'][] = [
                'partyIdType' => 'MSISDN',
                'partyId' => $recipient['phone_number'], // Set the recipient's phone number
            ];
        }

        $url = $this->base_url . '/disbursement/v1_0/transfer';

        $options = [
            'headers' => $headers,
            'json' => $body,
        ];

        try {
            $response = $this->client->post($url, $options);
            $data = json_decode($response->getBody(), true);

            return [
                'success' => true,
                'message' => 'Disbursement initiated successfully',
                'data' => $data,
            ];
        } catch (GuzzleException $e) {
            return [
                'success' => false,
                'message' => 'Disbursement initiation failed',
                'data' => $e->getMessage(),
            ];
        }
    }

    /**
     * initiate a single disbursement to a customer
     *
     * @param float $amount
     * @param string $currency currency symbol NGN
     * @param integer $payer phone number
     * @param integer $payee phone number
     * @return JsonResponse
     */
    public function initiateDisbursement(float $amount, int $payer, int $payee,string $currency="NGN"):JsonResponse
    {
        $url = $this->base_url . '/disbursement/v1_0/transfer';
        $body = [
            'amount' => $amount,
            'currency' => $currency,
            'externalId' => uniqid(),
            'payee' => [
                'partyIdType' => 'MSISDN',
                'partyId' => $payee
            ],
            'payer' => [
                'partyIdType' => 'MSISDN',
                'partyId' => $payer
            ],
            'payerMessage' => 'Disbursement message',
            'payeeNote' => 'Payee note',
            'callbackUrl' => $this->callback_url
        ];
        $response = Http::withHeaders([
            'Ocp-Apim-Subscription-Key' => $this->api_key,
            'Authorization' => 'Bearer ' . $this->generateAccessToken()
        ])->post($url, $body);
        return $response->json();
    }

    /**
     * generate momo access token
     *
     * @return string
     */
    private function generateAccessToken():string
    {
        $url = $this->base_url . '/disbursement/token/';
        $response = Http::withHeaders([
            'Ocp-Apim-Subscription-Key' => $this->api_key,
            'Authorization' => 'Basic ' . base64_encode($this->api_key . ':' . $this->api_secret)
        ])->get($url);
        $access_token = $response->json()['access_token'];
        return $access_token;
    }

    public function generateReference():string
    {
        return "";
    }
}
