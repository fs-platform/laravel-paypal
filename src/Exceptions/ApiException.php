<?php

namespace Smbear\Paypal\Exceptions;

class ApiException extends BaseException
{
    static public function handle(\Exception $exception): string
    {
        if (is_null($message = json_decode($exception->getMessage(),true))){
            return $exception->getMessage();
        }

        if (isset($message['error_description'],$message['error'])){
            return $message['error_description'];
        }

        if (isset($message['debug_id'],$message['message'])){
            return $message['message'];
        }

        return $exception->getMessage();
    }
}