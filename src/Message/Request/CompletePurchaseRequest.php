<?php

namespace Omnipay\Cardinity\Message\Request;

use Omnipay\Cardinity\Message\AbstractRequest;
use Omnipay\Cardinity\Message\Response\CompletePurchaseResponse;
use Omnipay\Common\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Request;

class CompletePurchaseRequest extends AbstractRequest
{
    private const ENDPOINT_METHOD = 'payments/{id}';

    public const TYPE_3DSV1 = 'type_3dsv1';
    public const TYPE_3DSV2 = 'type_3dsv2';

    private const TRANSACTION_ID_PARAM = 'transactionReference';
    private const CRES_PARAM = 'cres';
    private const PARES_PARAM = 'authorize_data';
    private const TYPE_PARAM = 'type';

    protected string $httpMethod = Request::METHOD_PATCH;

    private const VALIDATE_PARAMETERS = [
        self::TRANSACTION_ID_PARAM,
        self::TYPE_PARAM,
    ];


    protected function createResponse(string $bodyContents, array $headers, int $statusCode): ResponseInterface
    {
        return $this->response = new CompletePurchaseResponse($this, $bodyContents, $headers, $statusCode);
    }

    public function getEndpointMethod(): string
    {
        return str_replace('{id}', $this->getTransactionReference(), self::ENDPOINT_METHOD);
    }

    public function getData(): array
    {
        $this->validate(...self::VALIDATE_PARAMETERS);

        $data = [];

        if (self::TYPE_3DSV1 === $this->getParameter(self::TYPE_PARAM)) {
            $data[self::PARES_PARAM] = $this->getParameter(self::PARES_PARAM);
        }

        if (self::TYPE_3DSV2 === $this->getParameter(self::TYPE_PARAM)) {
            $data[self::CRES_PARAM] = $this->getParameter(self::CRES_PARAM);
        }

        return $data;
    }

    public function getTransactionReference(): string
    {
        return $this->getParameter(self::TRANSACTION_ID_PARAM);
    }

    public function getType(): string
    {
        return $this->getParameter(self::TYPE_PARAM);
    }

    public function setType(string $type): self
    {
        return $this->setParameter(self::TYPE_PARAM, $type);
    }

    public function getCres(): ?string
    {
        return $this->getParameter(self::CRES_PARAM);
    }

    public function setCres(?string $cres): self
    {
        return $this->setParameter(self::CRES_PARAM, $cres);
    }

    public function getAuthorizeData(): ?string
    {
        return $this->getParameter(self::PARES_PARAM);
    }

    public function setAuthorizeData(?string $pares): self
    {
        return $this->setParameter(self::PARES_PARAM, $pares);
    }
}
