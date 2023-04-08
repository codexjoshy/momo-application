<?php
    namespace App\Services\Contracts;


    interface SmsServiceInterface {
        public function sendSms(string $to, string $message, ?string $from = null): string|array;
        public function sendBulkSms(array $to, string $message, ?string $from = null): string|array;
        public function checkBalance(): string|array;
    }
?>
