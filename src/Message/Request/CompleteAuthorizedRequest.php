<?php

namespace Omnipay\Cardinity\Message\Request;

use Omnipay\Cardinity\Message\AbstractRequest;
use Omnipay\Cardinity\Message\Response\CompleteAuthorizedResponse;
use Omnipay\Common\Message\ResponseInterface;

class CompleteAuthorizedRequest extends AbstractRequest
{
    private const ENDPOINT_METHOD = 'payments/{id}/settlements';

    private const TRANSACTION_ID_PARAM = 'transactionReference';
    private const AMOUNT_PARAM = 'amount';
    private const DESCRIPTION_PARAM = 'description';

    private const VALIDATE_PARAMETERS = [
        self::TRANSACTION_ID_PARAM,
        self::AMOUNT_PARAM,
        self::DESCRIPTION_PARAM,
    ];

    protected function createResponse(string $bodyContents, array $headers, int $statusCode): ResponseInterface
    {
        return $this->response = new CompleteAuthorizedResponse($this, $bodyContents, $headers, $statusCode);
    }

    public function getEndpointMethod(): string
    {
        return str_replace('{id}', $this->getTransactionReference(), self::ENDPOINT_METHOD);
    }

    public function getData(): array
    {
        $this->validate(...self::VALIDATE_PARAMETERS);

        return [
            self::AMOUNT_PARAM => $this->getParameter(self::AMOUNT_PARAM),
            self::DESCRIPTION_PARAM => $this->getParameter(self::DESCRIPTION_PARAM),
        ];
    }
}
