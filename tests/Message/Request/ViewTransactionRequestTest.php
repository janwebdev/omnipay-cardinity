<?php

namespace Omnipay\Cardinity\Tests\Message\Request;

use Omnipay\Cardinity\Message\AbstractRequest;
use Omnipay\Cardinity\Message\Request\ViewTransactionRequest;
use Omnipay\Cardinity\Message\Response\ViewTransactionResponse;
use Omnipay\Common\Exception\InvalidRequestException;
use Symfony\Component\HttpFoundation\Request;

class ViewTransactionRequestTest extends AbstractRequestTest
{
    private AbstractRequest $request;

    private const TRANSACTION_REFERENCE = '90095d47-11bb-468b-8764-fd4fbb49a9f9';

    protected function setUp(): void
    {
        $this->request = $this->makeRequest(ViewTransactionRequest::class);
    }

    public function testValidationFails(): void
    {
        $this->expectException(InvalidRequestException::class);

        $this->request->getData();
    }

    public function testValidationPasses(): AbstractRequest
    {
        $parameters = [
            'transactionReference' => self::TRANSACTION_REFERENCE
        ];

        $purchaseRequest = $this->request->initialize($parameters);
        $purchaseRequestData = $purchaseRequest->getData();

        self::assertEmpty($purchaseRequestData);


        return $purchaseRequest;
    }

    /**
     * @depends testValidationPasses
     * @param ViewTransactionRequest $purchaseRequest
     */
    public function testHttpMethodIsGet(ViewTransactionRequest $purchaseRequest): void
    {
        self::assertEquals(Request::METHOD_GET, $purchaseRequest->getHttpMethod());
    }

    /**
     * @depends testValidationPasses
     * @param ViewTransactionRequest $purchaseRequest
     */
    public function testDataInstances(ViewTransactionRequest $purchaseRequest): void
    {
        self::assertInstanceOf(ViewTransactionResponse::class, $purchaseRequest->send());
    }
}
