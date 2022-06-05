<?php

namespace Omnipay\Cardinity\Entity;

use Omnipay\Cardinity\Contracts\CardInterface;
use Omnipay\Common\ParametersTrait;

class Card implements CardInterface
{
    use ParametersTrait;

    public function __construct(array $parameters)
    {
        $this->initialize($parameters);
    }

    public function setPan(string $pan): self
    {
        return $this->setParameter('pan', $pan);
    }

    public function getPan(): string
    {
        return $this->getParameter('pan');
    }

    public function setExpYear(string $expYear): self
    {
        return $this->setParameter('exp_year', $expYear);
    }

    public function getExpYear(): string
    {
        return $this->getParameter('exp_year');
    }

    public function setExpMonth(string $expMonth): self
    {
        return $this->setParameter('exp_month', $expMonth);
    }

    public function getExpMonth(): string
    {
        return $this->getParameter('exp_month');
    }

    public function setCvc(string $cvc): self
    {
        return $this->setParameter('cvc', $cvc);
    }

    public function getCvc(): string
    {
        return $this->getParameter('cvc');
    }

    public function setHolder(string $holder): self
    {
        return $this->setParameter('holder', $holder);
    }

    public function getHolder(): string
    {
        return $this->getParameter('holder');
    }

    public function toArray(): array
    {
        return $this->getParameters();
    }
}
