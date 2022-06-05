<?php

namespace Omnipay\Cardinity\Message\Response;

use Omnipay\Cardinity\Message\AbstractResponse;
use Symfony\Component\HttpFoundation\Response;

class ViewTransactionResponse extends AbstractResponse
{
    public function isSuccessful(): bool
    {
        return $this->getStatusCode() === Response::HTTP_OK && null !== $this->getTransactionReference();
    }

    public function getCard(): ?array
    {
        return $this->getValueFromData('payment_instrument');
    }

    public function getAmount(): ?string
    {
        return $this->getValueFromData('amount');
    }

    public function getCurrency(): ?string
    {
        return $this->getValueFromData('currency');
    }

    public function getCreatedAt(): ?\DateTime
    {
        $created = $this->getValueFromData('created');
        return $created ? \DateTime::createFromFormat(\DateTime::RFC3339, $created) : null;
    }

    public function getType(): ?string
    {
        return $this->getValueFromData('type');
    }

    public function isReal(): bool
    {
        return $this->getValueFromData('live') && true === $this->getValueFromData('live');
    }

    public function getStatus(): ?string
    {
        return $this->getValueFromData('status');
    }

    public function getOrderId(): ?string
    {
        return $this->getValueFromData('order_id');
    }

    public function getDescription(): ?string
    {
        return $this->getValueFromData('description');
    }

    public function getStatement(): ?string
    {
        return $this->getValueFromData('statement_descriptor_suffix');
    }

    public function getCountry(): ?string
    {
        return $this->getValueFromData('country');
    }

    public function get3DS2data(): ?array
    {
        return $this->getValueFromData('threeds2_data');
    }
}
