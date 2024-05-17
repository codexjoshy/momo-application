<?php

namespace App\Http\Controllers;

use App\Services\airtime\AirtimeProcessor;
use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory as Response;

class AjaxController extends Controller
{
    public function customerListPreview(Request $request, Response $response)
    {
        $file = $_FILES["file"]["tmp_name"];
        $totalAmount = $request->totalAmount;
        if (!$file) return $response->json(["status" => false, "data" => false, "error" => ["message" => 'file not found']]);

        if ($handle = fopen($file, 'r')) {
            $k = $amountSum = 0;
            $errors = [];
            $phones = [];
            while (($record = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($k != 0) {
                    [$phone, $amount] = $record;
                    $phone = (int) trim($phone);
                    $amount = trim($amount);
                    $records[] = $record;
                    // if (count($record) > 2) {
                    //     $errors[$k] = "record is more than 2, please check";
                    // }
                    if ($phone && $amount && $amount > 0) {
                        if (!filter_var($phone, FILTER_VALIDATE_INT)) $errors[$k] = "invalid phone provided,";
                        if (strlen($phone) < 10 || strlen($phone) > 13) $errors[$k] = "phone length " . strlen($phone) . " does not meet standards,";
                        if (in_array($phone, $phones)) $errors[$k] = "duplicate phone number,";
                        $last4Digit = "0" . substr($phone, 3, 3);
                        $operator = (new AirtimeProcessor)->detectOperator($last4Digit);
                        if (!$operator)  $errors[$k] = "invalid operator detected, ";

                        $phones[] = $phone;

                        if (!count($errors)) {
                            $data[] = ["phone" => $phone, "amount" => $amount, "operator" => $operator];
                        }
                    } else {
                        $errors[$k] =  "invalid data provided";
                    }

                    $amountSum += floatval($amount);
                }
                $k++;
            }
            if ($amountSum != $totalAmount) {
                $errors['message'] = "Total Amount recorded $amountSum does not match the amount entered $totalAmount";
                return $response->json(["status" => false, "data" => [], "error" => $errors, "records" => $records]);
            }
            if ($errors && count($errors)) {
                $errors['message'] = "There was an error in uploaded file";
                return $response->json(["status" => false, "data" => [], "error" => $errors, "records" => $records]);
            }
            return $response->json(["status" => true, "data" => $data, "error" => $errors, "records" => $records]);
        } else {
            return $response->json(["status" => false, "data" => [], "error" => ["message" => 'unable to handle file']]);
        }
    }
}
