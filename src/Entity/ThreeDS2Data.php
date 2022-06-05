<?php

namespace Omnipay\Cardinity\Entity;

use Omnipay\Cardinity\Contracts\ThreeDS2Interface;
use Omnipay\Common\ParametersTrait;

class ThreeDS2Data implements ThreeDS2Interface
{
    use ParametersTrait;

    public function __construct(array $parameters)
    {
        $this->initialize($parameters);
    }

    public function setNotificationUrl(string $url): ThreeDS2Interface
    {
        return $this->setParameter('notification_url', $url);
    }

    public function getNotificationUrl(): string
    {
        return $this->getParameter('notification_url');
    }

    public function setBrowserInfo(array $browserInfo): ThreeDS2Interface
    {
        $browserInfoObj = new BrowserInfo($browserInfo);

        return $this->setParameter('browser_info', $browserInfoObj);
    }

    public function getBrowserInfo(): array
    {
        return $this->getParameter('browser_info')->toArray();
    }

    public function toArray(): array
    {
        return [
            'notification_url' => $this->getNotificationUrl(),
            'browser_info' => $this->getBrowserInfo()
        ];
    }
}
