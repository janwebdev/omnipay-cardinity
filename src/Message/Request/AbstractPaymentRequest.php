<?php

namespace Omnipay\Cardinity\Message\Request;

use Omnipay\Cardinity\Contracts\CardInterface;
use Omnipay\Cardinity\Contracts\ThreeDS2Interface;
use Omnipay\Cardinity\Entity\Card;
use Omnipay\Cardinity\Entity\ThreeDS2Data;
use Omnipay\Cardinity\Message\AbstractRequest;
use Omnipay\Cardinity\Message\Response\PurchaseResponse;
use Omnipay\Common\Message\ResponseInterface;

abstract class AbstractPaymentRequest extends AbstractRequest
{
    private const ENDPOINT_METHOD = 'payments';

    private const AMOUNT_PARAM = 'amount';
    private const CURRENCY_PARAM = 'currency';
    private const SETTLE_PARAM = 'settle';
    private const DESCRIPTION_PARAM = 'description';
    private const ORDER_ID_PARAM = 'order_id';
    private const COUNTRY_PARAM = 'country';
    private const PAYMENT_METHOD_PARAM = 'payment_method';
    private const CARD_PARAM = 'payment_instrument';
    private const _3DS2_DATA_PARAM = 'threeds2_data';
    private const STATEMENT_DESCRIPTOR_PARAM = 'statement_descriptor_suffix';
    private const CALLBACK_URL = 'callback_url';

    private const VALIDATE_PARAMETERS = [
        self::AMOUNT_PARAM,
        self::CURRENCY_PARAM,
        self::DESCRIPTION_PARAM,
        self::ORDER_ID_PARAM,
        self::COUNTRY_PARAM,
        self::CARD_PARAM,
        self::CALLBACK_URL,
    ];

    /**
     * TRUE - if purchase, FALSE - if authorization
     */
    abstract public function getSettle(): bool;

    public function getEndpointMethod(): string
    {
        return self::ENDPOINT_METHOD;
    }

    protected function createResponse(string $bodyContents, array $headers, int $statusCode): ResponseInterface
    {
        return $this->response = new PurchaseResponse(
            $this,
            $bodyContents,
            $headers,
            $statusCode,
            $this->getParameter(self::CALLBACK_URL)
        );
    }

    public function getData(): array
    {
        $this->validate(...self::VALIDATE_PARAMETERS);

        $data = [
            self::AMOUNT_PARAM => $this->getAmount(),
            self::CURRENCY_PARAM => $this->getCurrency(),
            self::SETTLE_PARAM => $this->getSettle(),
            self::DESCRIPTION_PARAM => $this->getDescription(),
            self::ORDER_ID_PARAM => $this->getOrderId(),
            self::COUNTRY_PARAM => $this->getCountry(),
            self::PAYMENT_METHOD_PARAM => $this->getPaymentMethod(),
            self::CARD_PARAM => $this->getPaymentInstrument(),
            self::CALLBACK_URL => $this->getCallbackUrl(),
        ];

        if ($this->getThreeDS2Data()) {
            $data[self::_3DS2_DATA_PARAM] = $this->getThreeDS2Data();
        }

        if ($this->getStatementDescriptorSuffix()) {
            $data[self::STATEMENT_DESCRIPTOR_PARAM] = $this->getStatementDescriptorSuffix();
        }

        return $data;
    }

    public function getAmount(): string
    {
        return $this->getParameter(self::AMOUNT_PARAM);
    }

    public function setAmount($amount): self
    {
        return $this->setParameter(self::AMOUNT_PARAM, $amount);
    }

    public function getCurrency(): string
    {
        return $this->getParameter(self::CURRENCY_PARAM);
    }

    public function setCurrency($value): self
    {
        return $this->setParameter(self::CURRENCY_PARAM, $value);
    }

    public function getDescription(): string
    {
        return $this->getParameter(self::DESCRIPTION_PARAM);
    }

    public function setDescription($value): self
    {
        return $this->setParameter(self::DESCRIPTION_PARAM, $value);
    }

    public function getOrderId(): string
    {
        return $this->getParameter(self::ORDER_ID_PARAM);
    }

    public function setOrderId(string $orderId): self
    {
        return $this->setParameter(self::ORDER_ID_PARAM, $orderId);
    }

    public function getCountry(): string
    {
        return $this->getParameter(self::COUNTRY_PARAM);
    }

    public function setCountry(string $countryISO): self
    {
        return $this->setParameter(self::COUNTRY_PARAM, $countryISO);
    }

    public function getPaymentMethod(): string
    {
        return 'card';
    }

    public function getPaymentInstrument(): array
    {
        return $this->getParameter(self::CARD_PARAM)->toArray();
    }

    public function setPaymentInstrument(array $cardData): self
    {
        if (!($cardData instanceof CardInterface)) {
            $card = new Card($cardData);
        }

        return $this->setParameter(self::CARD_PARAM, $card);
    }

    public function getThreeDS2Data(): ?array
    {
        return $this->getParameter(self::_3DS2_DATA_PARAM) ?
            $this->getParameter(self::_3DS2_DATA_PARAM)->toArray() :
            null
            ;
    }

    public function setThreeDS2Data(array $data): self
    {
        if (!($data instanceof ThreeDS2Interface)) {
            $_3dsData = new ThreeDS2Data($data);
        }

        return $this->setParameter(self::_3DS2_DATA_PARAM, $_3dsData);
    }

    public function getStatementDescriptorSuffix(): ?string
    {
        return $this->getParameter(self::STATEMENT_DESCRIPTOR_PARAM);
    }

    public function setStatementDescriptorSuffix(string $statement): self
    {
        return $this->setParameter(self::STATEMENT_DESCRIPTOR_PARAM, $statement);
    }

    public function getCallbackUrl(): ?string
    {
        return $this->getParameter(self::CALLBACK_URL);
    }

    public function setCallbackUrl(string $url): self
    {
        return $this->setParameter(self::CALLBACK_URL, $url);
    }
}
