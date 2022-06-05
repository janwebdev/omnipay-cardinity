<?php

namespace Omnipay\Cardinity\Message\Response;

use Omnipay\Cardinity\Message\AbstractResponse;
use Symfony\Component\HttpFoundation\Response;

class CompleteAuthorizedResponse extends AbstractResponse
{
    public function isSuccessful(): bool
    {
        return $this->getStatusCode() === Response::HTTP_CREATED &&
            null !== $this->getTransactionReference() &&
            'approved' === $this->getValueFromData('status')
            ;
    }

    public function isSettlement(): bool
    {
        return $this->isStatusCodeCreated() && 'settlement' === $this->getValueFromData('type');
    }

    public function isDeclined(): bool
    {
        return $this->getStatusCode() === Response::HTTP_PAYMENT_REQUIRED &&
            'approved' !== $this->getValueFromData('status')
            ;
    }

    public function getAuthorizedPaymentId(): ?string
    {
        return $this->getValueFromData('parent_id');
    }

    public function getError(): ?string
    {
        return $this->getValueFromData('error');
    }
}
