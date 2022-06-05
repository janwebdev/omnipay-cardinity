<?php

namespace Omnipay\Cardinity\Message\Response;

use Omnipay\Cardinity\Message\AbstractResponse;
use Symfony\Component\HttpFoundation\Response;

class CompletePurchaseResponse extends AbstractResponse
{
    public function isSuccessful(): bool
    {
        return $this->getStatusCode() === Response::HTTP_CREATED &&
            null !== $this->getTransactionReference() &&
            'approved' === $this->getValueFromData('status')
            ;
    }

    public function isDeclined(): bool
    {
        return $this->isStatusNeedPayment() && 'declined' === $this->getValueFromData('status');
    }
}
