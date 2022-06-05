<?php

namespace Omnipay\Cardinity;

use Omnipay\Cardinity\Message\Request\AuthorizedRequest;
use Omnipay\Cardinity\Message\Request\CompleteAuthorizedRequest;
use Omnipay\Cardinity\Message\Request\CompletePurchaseRequest;
use Omnipay\Cardinity\Message\Request\PurchaseRequest;
use Omnipay\Cardinity\Message\Request\RefundPurchaseRequest;
use Omnipay\Cardinity\Message\Request\ViewTransactionRequest;
use Omnipay\Cardinity\Message\Request\VoidRequest;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Cardinity\Traits\ApiCredentialsTrait;
use Omnipay\Common\AbstractGateway;

/**
 * @link https://developers.cardinity.com/api/v1/?shell#introduction
 *
 * @method \Omnipay\Common\Message\NotificationInterface acceptNotification(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface capture(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface createCard(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface updateCard(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface deleteCard(array $options = array())
 */
class Gateway extends AbstractGateway
{
    use ApiCredentialsTrait;

    private const GATEWAY_CLASS = 'Cardinity';

    private const GATEWAY_NAME = 'Cardinity gateway';

    public function getName(): string
    {
        return self::GATEWAY_NAME;
    }

    public static function getGatewayClass(): string
    {
        return self::GATEWAY_CLASS;
    }

    public function getDefaultParameters(): array
    {
        return [
            self::$consumerKey => '',
            self::$consumerSecret => ''
        ];
    }

    public function authorize(array $options = []): AbstractRequest
    {
        return $this->createRequest(AuthorizedRequest::class, $options);
    }

    public function completeAuthorize(array $options = []): AbstractRequest
    {
        return $this->createRequest(CompleteAuthorizedRequest::class, $options);
    }

    public function purchase(array $options = []): AbstractRequest
    {
        return $this->createRequest(PurchaseRequest::class, $options);
    }

    public function completePurchase(array $options = []): AbstractRequest
    {
        return $this->createRequest(CompletePurchaseRequest::class, $options);
    }

    public function fetchTransaction(array $options = []): AbstractRequest
    {
        return $this->createRequest(ViewTransactionRequest::class, $options);
    }

    public function refund(array $options = []): AbstractRequest
    {
        return $this->createRequest(RefundPurchaseRequest::class, $options);
    }

    /**
     * Voids are available for authorizations only!
     */
    public function void(array $options = []): AbstractRequest
    {
        return $this->createRequest(VoidRequest::class, $options);
    }
}
