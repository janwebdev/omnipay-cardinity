<?php

namespace Omnipay\Cardinity\Message\Response;

use Omnipay\Cardinity\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Symfony\Component\HttpFoundation\Request;

class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    private const STATUS_APPROVED = 'approved';
    private const STATUS_PENDING = 'pending';
    private const STATUS_DECLINED = 'declined';

    private const TYPE_AUTHORIZATION = 'authorization';

    public function isRedirect()
    {
        $_3DSv1 = $this->getValueFromData('authorization_information');
        $_3DSv2 = $this->getValueFromData('threeds2_data');

        if (isset($_3DSv1['url']) || isset($_3DSv2['acs_url'])) {
            return true;
        }

        return false;
    }

    public function getRedirectUrl(): ?string
    {
        if ($this->isRedirect()) {
            $_3DSv1 = $this->getValueFromData('authorization_information');
            if (isset($_3DSv1['url'])) {
                return $_3DSv1['url'];
            }

            $_3DSv2 = $this->getValueFromData('threeds2_data');
            if (isset($_3DSv2['acs_url'])) {
                return $_3DSv2['acs_url'];
            }
            return null;
        }
        return null;
    }

    public function getRedirectMethod(): string
    {
        return Request::METHOD_POST;
    }

    public function getRedirectData(): array
    {
        if ($this->is3DSv1()) {
            $_3DSv1 = $this->getValueFromData('authorization_information');
            return [
                'PaReq' => $_3DSv1['data'],
                'TermUrl' => $this->getCallbackUrl(),
                'MD' => $this->getValueFromData('order_id')
            ];
        }

        if ($this->is3DSv2()) {
            $_3DSv2 = $this->getValueFromData('threeds2_data');
            return [
                'creq' => $_3DSv2['creq'],
                'threeDSSessionData' => $this->getValueFromData('order_id')
            ];
        }

        return [];
    }

    public function is3DSv1(): bool
    {
        return null !==  $this->getValueFromData('authorization_information');
    }

    public function is3DSv2(): bool
    {
        return null !==  $this->getValueFromData('threeds2_data');
    }

    public function isSuccessful(): bool
    {
        return $this->isStatusCodeCreated() && self::STATUS_APPROVED === $this->getValueFromData('status');
    }

    public function isPending(): bool
    {
        return $this->isStatusCodePending() && self::STATUS_PENDING === $this->getValueFromData('status');
    }

    public function isDeclined(): bool
    {
        return $this->isStatusCodeCreated() && self::STATUS_DECLINED === $this->getValueFromData('status');
    }

    public function isAuthorized(): bool
    {
        return ($this->isStatusCodeCreated() || $this->isStatusCodePending()) &&
            self::TYPE_AUTHORIZATION === $this->getValueFromData('type')
            ;
    }
}
