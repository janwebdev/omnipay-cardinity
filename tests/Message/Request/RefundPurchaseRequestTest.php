<?php

namespace Omnipay\Cardinity\Tests\Message\Request;

use Omnipay\Cardinity\Message\AbstractRequest;
use Omnipay\Cardinity\Message\Request\RefundPurchaseRequest;
use Omnipay\Cardinity\Message\Response\RefundPurchaseResponse;
use Omnipay\Common\Exception\InvalidRequestException;
use Symfony\Component\HttpFoundation\Request;

class RefundPurchaseRequestTest extends AbstractRequestTest
{
    private AbstractRequest $request;

    private const TRANSACTION_REFERENCE = '90095d47-11bb-468b-8764-fd4fbb49a9f9';
    private const AMOUNT = '15.00';
    private const DESCRIPTION = 'Lorem ipdum dolor sit amet';

    protected function setUp(): void
    {
        $this->request = $this->makeRequest(RefundPurchaseRequest::class);
    }

    public function testValidationFails(): void
    {
        $this->expectException(InvalidRequestException::class);

        $this->request->getData();
    }

    public function testValidationPasses(): AbstractRequest
    {
        $parameters = [
            'transactionReference' => self::TRANSACTION_REFERENCE,
            'amount' => self::AMOUNT,
            'description' => self::DESCRIPTION,
        ];

        $purchaseRequest = $this->request->initialize($parameters);
        $purchaseRequestData = $purchaseRequest->getData();

        self::assertArrayHasKey('amount', $purchaseRequestData);
        self::assertEquals(self::AMOUNT, $purchaseRequestData['amount']);
        self::assertArrayHasKey('description', $purchaseRequestData);
        self::assertEquals(self::DESCRIPTION, $purchaseRequestData['description']);


        return $purchaseRequest;
    }

    /**
     * @depends testValidationPasses
     * @param RefundPurchaseRequest $purchaseRequest
     */
    public function testHttpMethodIsPost(RefundPurchaseRequest $purchaseRequest): void
    {
        self::assertEquals(Request::METHOD_POST, $purchaseRequest->getHttpMethod());
    }

    /**
     * @depends testValidationPasses
     * @param RefundPurchaseRequest $purchaseRequest
     */
    public function testDataInstances(RefundPurchaseRequest $purchaseRequest): void
    {
        self::assertInstanceOf(RefundPurchaseResponse::class, $purchaseRequest->send());
    }
}
