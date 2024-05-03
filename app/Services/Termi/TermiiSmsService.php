<?php

namespace App\Services\Termi;

use Illuminate\Support\Facades\Response as JsonResponse;

use Illuminate\Support\Facades\Http;
use App\Services\Contracts\SmsServiceInterface;
use Illuminate\Support\Facades\Log;

// use Illuminate\Support\Facades\Http;
class TermiiSmsService implements SmsServiceInterface
{
    /**
     * The base URL of the Termii API.
     */
    protected string $apiUrl;

    /**
     * The base URL of the Termii API.
     */
    protected $httpResponse;


    /**
     * The API key used to authenticate requests.
     */
    protected string $apiKey;

    /**
     * The API key used to authenticate requests.
     */
    protected string $from;

    /**
     * The API key used to authenticate requests.
     */
    protected string $_channel;

    protected HTTPHandler $httpHandler;

    public function __construct()
    {
        $this->apiKey = config("termii.apiKey");
        $this->apiUrl = config("termii.baseUrl");
        $this->from = config("termii.from");
        $this->_channel = config("termii.channel");
        $this->httpHandler = new HTTPHandler;
    }

    /**
     * Send an SMS message to a single recipient.
     *
     * @param string $to The phone number of the recipient. Must be in international format (e.g. "+234xxxxxxxxxx").
     * @param string $message The message to send.
     * @param string|null $from (optional) The sender ID to use. Must be a valid registered sender ID. If not specified, the default sender ID will
     * be used.
     *
     * @return array The response from the Termii API as an associative array.
     */
    public function sendSms(string $to, string $message, ?string $from = null): array
    {
        $payload = [
            'to' => $to,
            'sms' => $message,
            'api_key' => $this->apiKey,
        ];

        if ($from) {
            $payload['from'] = $from;
        }

        $this->httpResponse = Http::post($this->apiUrl . '/sms/send', $payload);

        $response = $this->getResponse();

        if ($response['status']) {
            return [
                "currency" => $response['data']['currency'] ?? '',
                "balance" => $response['data']['balance'] ?? ''
            ];
        }
    }

    /**
     * Send an SMS message to multiple recipients.
     *
     * @param array $to An array of phone numbers of the recipients. Each phone number must be in international format (e.g. "+234xxxxxxxxxx").
     * @param string $message The message to send.
     * @param string|null $from (optional) The sender ID to use. Must be a valid registered sender ID. If not specified, the default sender ID will be used.
     *
     * @return array The response from the Termii API as an associative array.
     */
    public function sendBulkSms(array $to, string $message, ?string $from = null): array
    {
        $payload = [
            'to' =>  $to,
            'type' => 'plain',
            // 'to' => implode(',', $to),
            'sms' => $message,
            'api_key' => $this->apiKey,
            'from' => $this->from,
            'channel' => $this->_channel,
        ];
        if ($from) {
            $payload['from'] = $from;
        }
        // dd($payload);
        // $this->httpResponse = Http::post($this->apiUrl . '/sms/send', $payload);
        // $response = $this->getResponse();
        $response = $this->httpHandler->makeRequest($this->apiUrl . 'sms/send', 'POST', $payload,);
        $this->logger("response for sms sending by termii", json_encode($response));
        $code = $response['code'];
        if (strtolower($code) == "ok") {
            return [
                "status" => true,
                "messageId" => $response['message_id'] ?? null,
                "data" => $response
            ];
        }

        return [
            "status" => false,
            "messageId" => null,
            "data" => $response
        ];
    }

    /**
     * Check the remaining balance on the Termii account.
     *
     * @return array
     */
    public function checkBalance(): array
    {
        // dd($this->apiUrl);
        try {
            $this->httpResponse = Http::get($this->apiUrl . 'get-balance', [
                'api_key' => $this->apiKey,
            ]);
            $response = $this->getResponse();
            throw_if(!$response['status'], $response['message']);
            if ($response['status']) {
                return [
                    "currency" => $response['data']['currency'] ?? '',
                    "balance" => $response['data']['balance'] ?? ''
                ];
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        return [
            "currency" => null,
            "balance" => null
        ];
    }

    /**
     * Register a new Sender ID with Termii.
     *
     * @param string $sender_id The Sender ID to register.
     * @param string $company_name The name of the company associated with the Sender ID.
     * @param string $company_email The email address of the company associated with the Sender ID.
     * @param string $company_website The website of the company associated with the Sender ID.
     *
     * @return array The response from the Termii API as an associative array.
     */
    public function registerSenderID(string $sender_id, string $company_name, string $company_email, string $company_website): array
    {
        $this->httpResponse = Http::post($this->apiUrl . '/senderid/register', [
            'apiKey' => $this->apiKey,
            'sender_id' => $sender_id,
            'company_name' => $company_name,
            'company_email' => $company_email,
            'company_website' => $company_website,
        ]);

        $response = $this->getResponse();
        if ($response['status']) {
            return [
                "currency" => $response['data']['currency'] ?? '',
                "balance" => $response['data']['balance'] ?? ''
            ];
        }
    }

    /**
     * Get the delivery status of an SMS message that has been sent previously.
     *
     * @param string $messageId The ID of the SMS message to retrieve the delivery status for.
     *
     * @return array The response from the Termii API as an associative array.
     */
    public function getDeliveryStatus(string $messageId): array
    {
        $this->httpResponse = Http::get($this->apiUrl . '/status', [
            'api_key' => $this->apiKey,
            'message_id' => $messageId,
        ]);

        $response = $this->getResponse();
        if ($response['status']) {
            return [
                "currency" => $response['data']['currency'] ?? '',
                "balance" => $response['data']['balance'] ?? ''
            ];
        }
    }

    /**
     * Check the status of a registered Sender ID with Termii.
     *
     * @param string $senderId The Sender ID to check the status of.
     *
     * @return array The response from the Termii API as an associative array.
     */
    public function checkSenderIdStatus(string $senderId): array
    {
        $this->httpResponse = Http::get($this->apiUrl . '/senderid/status', [
            'apiKey' => $this->apiKey,
            'sender_id' => $senderId,
        ]);

        $response = $this->getResponse();
        if ($response['status']) {
            return [
                "currency" => $response['data']['currency'] ?? '',
                "balance" => $response['data']['balance'] ?? ''
            ];
        }
    }

    /**
     * Deletes a Sender ID registered with Termii.
     *
     * @param string $senderId The Sender ID to delete.
     *
     * @return array The response from the Termii API as an associative array.
     */
    public function deleteSenderID(string $senderId): array
    {
        $this->httpResponse = Http::post($this->apiUrl . '/senderid/delete', [
            'apiKey' => $this->apiKey,
            'sender_id' => $senderId,
        ]);

        $response = $this->getResponse();
        if ($response['status']) {
            return [
                "currency" => $response['data']['currency'] ?? '',
                "balance" => $response['data']['balance'] ?? ''
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
