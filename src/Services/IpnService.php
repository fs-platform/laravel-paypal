<?php

namespace Smbear\Paypal\Services;

use Illuminate\Support\Facades\Http;
use Smbear\Paypal\Enums\PaypalEnums;

class IpnService
{
    /**
     * @Notes:
     *
     * @param string $quest
     * @param string $environment
     * @Author: smile
     * @Date: 2021/7/15
     * @Time: 16:51
     * @return bool
     */
    public function send(string $quest,string $environment): bool
    {
        $url = $environment == 'sandbox' ? PaypalEnums::SANDBOX_VERIFY_URI : PaypalEnums::VERIFY_URI;

        $response = Http::withOptions([
            'verify' => false
        ])
            ->timeout(30)
            ->withHeaders([
                'User-Agent: PHP-IPN-Verification-Script',
                'Connection: Close'
            ])
            ->withBody($quest,'raw')
            ->post($url);

        if ($response->successful()) {
            $response = $response->body();

            if (PaypalEnums::VALID == (string) $response) {
                return true;
            }
        }

        return false;
    }
}