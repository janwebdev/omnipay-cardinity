<?php

namespace Omnipay\Cardinity\Message\Request;

class PurchaseRequest extends AbstractPaymentRequest
{
    public function getSettle(): bool
    {
        return true;
    }
}
