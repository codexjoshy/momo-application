<?php
namespace App\Services\Termi;

/**
 * ErrorLog
 *
 * A class for managing HTTP request
 * @author      Joshua E. <joshua.adebayo@alabiansolutions.com>
 * @copyright   Alabian Solutions Limited
 * @version 	1.0 => March 2021
 * @link        alabiansolutions.com
 */
class HTTPHandler
{
    /** @var array headers for the http request */
    protected $headers = [];

    public function __construct()
    {

        if (!count($this->headers)) {
            $headers = array(
                "Accept: application/json",
                "Content-Type: application/json",
                // 'Cookie: PHPSESSID=03fcce6ac21db5581ef299debaabbf4c'
            );
        }
        $this->setHeaders($headers);
    }
    /**
     * used to redirect to a location in the projerct directory
     *
     * @param [string] $url
     * @return void
     */
    public static function redirectTo($url)
    {
        header("Location: $url");
        exit;
    }
    /**
     * used to transform a message to array
     *
     * @param bool $status true if success, false if failure
     * @param string $message the message to be displayed
     * @return array
     */
    private function response($status, $message)
    {
        return ["status" => $status, "data" => $message];
    }
    /**
     * used to make an http request to a known
     *@param string $url the url to be requested
     *@param string $method the method to be used for the request (GET, POST, PUT, DELETE)
     *@param array $data the data to be sent in the request body (optional)
     *@param array $headers the headers to be sent in the request (optional)
     *@return array
     */
    public function client($url, $method = 'GET', $data = [], $headers = null)
    {
        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_HTTPHEADER => $headers
            ));
            if ($data) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            }
            $response = curl_exec($curl);
            curl_close($curl);
        } catch (\Throwable $th) {
            throw $th;
        }
        $response = ($response) ? json_decode($response, true) : [];
        return [
            'code' => isset($response['statusCode']) ? $response['statusCode'] : 500,
            'status' => isset($response['success']) ? $response['success'] : false,
            'data' => isset($response['data']) ? $response['data'] : [],
            'message' => isset($response['messages']) ? $response['messages'] : ["Something went wrong, no data received"],
        ];
    }
    /**
     * used to set the headers
     *
     * @param array $headers
     * @return void
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
    }
    /**
     * used to handle get requests
     *@param string $url the url to be requested
     * @return array $response
     */
    public function get($url, $headers = null)
    {
        $headers = ($headers) ? $headers : $this->headers;
        try {
            $response =  $this->client($url, 'GET', null, $headers);
            $data = $response['data'];
            $status = $response['status'];
            $message = $response['message'];
            if (!$status) {
                return $this->response(false, $message);
            }
            return $this->response(true, $data);
        } catch (\Throwable $th) {
            return $this->response(false, [$th->getMessage()]);
        }
    }
    /**
     * used to handle post requests
     *@param string $url the url to be requested
     *@param array $data the data to be sent in the request body
     * @return array $response
     */
    public function post($url, $data = [], $headers = null)
    {
        $headers = ($headers) ? $headers : $this->headers;
        // Functions::displayJson($headers);
        try {
            $response =  $this->client($url, 'POST', $data, $headers);
            $data = $response['data'];
            $status = $response['status'];
            $message = $response['message'];
            if (!$status) {
                return $this->response(false, $message);
            }
            return $this->response(true, $data);
        } catch (\Throwable $th) {
            return $this->response(false, $th->getMessage());
        }
    }
    /**
     * used to handle put requests
     *@param string $url the url to be requested
     *@param array $data the data to be sent in the request body
     * @return array $response
     */
    public function put($url, $data = [], $headers = null)
    {
        try {
            $headers = ($headers) ? $headers : $this->headers;
            $response =  $this->client($url, 'PUT', $data, $headers);
            $data = $response['data'];
            $status = $response['status'];
            $message = $response['message'];
            if (!$status) {
                return $this->response(false, $message);
            }
            return $this->response(true, $data);
        } catch (\Throwable $th) {
            return $this->response(false, $th->getMessage());
        }
    }
    /**
     * used to handle patch requests
     *@param string $url the url to be requested
     *@param array $data the data to be sent in the request body
     * @return array $response
     */
    public function patch($url, $data = [], $headers = null)
    {
        try {
            $headers = ($headers) ? $headers : $this->headers;
            $response =  $this->client($url, 'PATCH', $data, $headers);
            $data = $response['data'];
            $status = $response['status'];
            $message = $response['message'];
            if (!$status) {
                return $this->response(false, $message);
            }
            return $this->response(true, $data);
        } catch (\Throwable $th) {
            return $this->response(false, $th->getMessage());
        }
    }
    /**
     * used to handle delete requests
     *@param string $url the url to be requested
     * @param array $data the data to be sent in the request body
     * @param array $headers the headers to be sent in the request
     * @return array $response
     */
    public function delete($url, $data = [], $headers = null)
    {
        try {
            $headers = ($headers) ? $headers : $this->headers;
            $response =  $this->client($url, 'DELETE', $data, $headers);
            $data = $response['data'];
            $status = $response['status'];
            $message = $response['message'];
            if (!$status) {
                return $this->response(false, $message);
            }
            return $this->response(true, $data);
        } catch (\Throwable $th) {
            return $this->response(false, $th->getMessage());
        }
    }
    /**
     * used to handle get requests headers
     * @return array $headers
     */
    public function getRequestHeaders()
    {
        $headers = array();
        foreach ($_SERVER as $key => $value) {
            if (substr($key, 0, 5) <> 'HTTP_') {
                continue;
            }
            $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
            $headers[$header] = $value;
        }
        return $headers;
    }

    /**
     * used to make external requests
     * @param string $url the url to be requested
     * @param string $method the method to be used
     * @param array $data the data to be sent in the request body
     * @param array $headers the headers to be sent in the request
     * @param array $curlOptions the headers to be sent in the request
     * @return array $response
     */
    public function makeRequest($url, $method, $data = [], $headers = [], array $curlOptions=[]):array
    {
        if (!$headers) {
            $headers = array(
                "Accept: application/json",
                "Content-Type: application/json",
                // 'Cookie: PHPSESSID=03fcce6ac21db5581ef299debaabbf4c'
            );
        }
        try {
            $curl = curl_init();
            $curlInfo = [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => strtoupper($method),
                CURLOPT_HTTPHEADER => $headers,
            ];
            if ($curlOptions) {
                foreach ($curlOptions as $key => $value) {
                    $curlInfo[$key] = $value;
                }
            }
            if ($data) {
                $curlInfo[CURLOPT_POSTFIELDS] = json_encode($data);
            }
            curl_setopt_array($curl, $curlInfo);
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                throw new \Exception($err, 1);

                //TODO: log error to file and send mail to developer
            }else {
                return json_decode($response, true);
            }
        } catch (\Throwable $th) {
            throw $th;
        }


    }
}
