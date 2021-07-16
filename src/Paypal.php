<?php

namespace Smbear\Paypal;

use Smbear\Paypal\Enums\PaypalEnums;
use Smbear\Paypal\Traits\PaypalOrder;
use Smbear\Paypal\Traits\PaypalConfig;
use Smbear\Paypal\Services\IpnService;
use Smbear\Paypal\Services\PaypalService;

class Paypal
{
    use PaypalOrder ,PaypalConfig;

    /**
     * @var object PaypalService payment service
     */
    public $paypalService;

    /**
     * @var object IpnService ipn service
     */
    public $ipnService;

    public function __construct()
    {
        $this->setEnvironment();

        $this->paypalService = new PaypalService();

        $this->ipnService = new IpnService();
    }

    /**
     * @Notes:初始化
     *
     * @throws Exceptions\ConfigException|Exceptions\MethodException
     * @return array
     * @Author: smile
     * @Date: 2021/6/30
     * @Time: 18:41
     */
    public function init(): array
    {
        //获取到指定环境的参数
        $this->getConfig([
            'client_id',
            'return_url',
            'cancel_url',
            'client_secret',
        ]);

        //验证方法是否被调用
        $this->checkMethod([
            'setAmount'      => 'amount',
            'setReferenceId' => 'referenceId',
        ]);

        return $this->paypalService->init($this->config,$this->getParameters());
    }

    /**
     * @Notes:获取到订单的状态
     *
     * @param string $token
     * @return array
     * @throws Exceptions\ConfigException
     * @Author: smile
     * @Date: 2021/6/30
     * @Time: 20:41
     */
    public function status(string $token) : array
    {
        //获取到指定环境的参数
        $this->getConfig([
            'client_id',
            'client_secret'
        ]);

        return $this->paypalService->status($this->config,$token);
    }

    /**
     * @Notes:ipn监听验证数据
     *
     * @param array $data
     * @Author: smile
     * @Date: 2021/7/15
     * @Time: 15:48
     * @return bool
     */
    public function ipn(array $data) : bool
    {
        $cmd = PaypalEnums::CMD;

        foreach ($data as $key => $value) {
            $value = urlencode($value);

            $cmd .= "&$key=$value";
        }

        return $this->ipnService->send($cmd,$this->environment);
    }
}