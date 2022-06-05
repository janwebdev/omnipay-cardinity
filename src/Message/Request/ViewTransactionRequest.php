<?php

namespace Omnipay\Cardinity\Message\Request;

use Omnipay\Cardinity\Message\AbstractRequest;
use Omnipay\Cardinity\Message\Response\ViewTransactionResponse;
use Omnipay\Common\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Request;

class ViewTransactionRequest extends AbstractRequest
{
    private const ENDPOINT_METHOD = 'payments/{id}';

    private const TRANSACTION_ID_PARAM = 'transactionReference';

    protected string $httpMethod = Request::METHOD_GET;

    protected function createResponse(string $bodyContents, array $headers, int $statusCode): ResponseInterface
    {
        return $this->response = new ViewTransactionResponse($this, $bodyContents, $headers, $statusCode);
    }

    public function getEndpointMethod(): string
    {
        return str_replace('{id}', $this->getTransactionReference(), self::ENDPOINT_METHOD);
    }

    public function getData(): array
    {
        $this->validate(...[self::TRANSACTION_ID_PARAM]);

        return [];
    }
}
