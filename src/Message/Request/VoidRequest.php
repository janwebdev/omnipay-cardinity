<?php

namespace Omnipay\Cardinity\Message\Request;

use Omnipay\Cardinity\Message\AbstractRequest;
use Omnipay\Cardinity\Message\Response\RefundPurchaseResponse;
use Omnipay\Cardinity\Message\Response\VoidResponse;
use Omnipay\Common\Message\ResponseInterface;

class VoidRequest extends AbstractRequest
{
    private const ENDPOINT_METHOD = 'payments/{id}/voids';

    private const TRANSACTION_ID_PARAM = 'transactionReference';
    private const DESCRIPTION_PARAM = 'description';

    private const VALIDATE_PARAMETERS = [
        self::TRANSACTION_ID_PARAM,
    ];

    protected function createResponse(string $bodyContents, array $headers, int $statusCode): ResponseInterface
    {
        return $this->response = new VoidResponse($this, $bodyContents, $headers, $statusCode);
    }

    public function getEndpointMethod(): string
    {
        return str_replace('{id}', $this->getTransactionReference(), self::ENDPOINT_METHOD);
    }

    public function getData(): array
    {
        $this->validate(...self::VALIDATE_PARAMETERS);

        return [
            self::DESCRIPTION_PARAM => $this->getDescription()
        ];
    }
}
