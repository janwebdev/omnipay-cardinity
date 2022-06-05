<?php

namespace Omnipay\Cardinity\Message\Request;

class AuthorizedRequest extends AbstractPaymentRequest
{
    public function getSettle(): bool
    {
        return false;
    }
}
