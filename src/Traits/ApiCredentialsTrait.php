<?php

namespace Omnipay\Cardinity\Traits;

trait ApiCredentialsTrait
{
    private static $consumerKey = 'consumerKey';

    private static $consumerSecret = 'consumerSecret';

    public function getConsumerKey(): string
    {
        return $this->getParameter(self::$consumerKey);
    }

    public function setConsumerKey(string $consumerKey): self
    {
        return $this->setParameter(self::$consumerKey, $consumerKey);
    }

    public function getConsumerSecret(): string
    {
        return $this->getParameter(self::$consumerSecret);
    }

    public function setConsumerSecret(string $consumerSecret): self
    {
        return $this->setParameter(self::$consumerSecret, $consumerSecret);
    }
}
