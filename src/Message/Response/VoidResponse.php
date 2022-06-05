<?php

namespace Omnipay\Cardinity\Message\Response;

use Omnipay\Cardinity\Message\AbstractResponse;
use Symfony\Component\HttpFoundation\Response;

class VoidResponse extends AbstractResponse
{
    private const STATUS_APPROVED = 'approved';
    private const STATUS_DECLINED = 'declined';

    public function isSuccessful(): bool
    {
        return $this->getStatusCode() === Response::HTTP_CREATED &&
            null !== $this->getTransactionReference() &&
            self::STATUS_APPROVED === $this->getValueFromData('status')
            ;
    }

    public function isVoid(): bool
    {
        return 'void' === $this->getValueFromData('type');
    }

    public function isDeclined(): bool
    {
        return $this->getStatusCode() === Response::HTTP_PAYMENT_REQUIRED &&
            self::STATUS_DECLINED === $this->getValueFromData('status')
            ;
    }

    public function getParentPurchaseId(): ?string
    {
        return $this->getValueFromData('parent_id');
    }
}
