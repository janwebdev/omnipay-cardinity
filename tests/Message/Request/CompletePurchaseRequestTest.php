<?php

namespace Omnipay\Cardinity\Tests\Message\Request;

use Omnipay\Cardinity\Message\AbstractRequest;
use Omnipay\Cardinity\Message\Request\CompletePurchaseRequest;
use Omnipay\Cardinity\Message\Response\CompletePurchaseResponse;
use Omnipay\Common\Exception\InvalidRequestException;
use Symfony\Component\HttpFoundation\Request;

class CompletePurchaseRequestTest extends AbstractRequestTest
{
    private AbstractRequest $request;

    private const TRANSACTION_REFERENCE = '90095d47-11bb-468b-8764-fd4fbb49a9f9';

    protected function setUp(): void
    {
        $this->request = $this->makeRequest(CompletePurchaseRequest::class);
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
            'type' => CompletePurchaseRequest::TYPE_3DSV2,
            'cres' => '123456789'
        ];

        $purchaseRequest = $this->request->initialize($parameters);
        $purchaseRequestData = $purchaseRequest->getData();

        self::assertArrayHasKey('cres', $purchaseRequestData);
        self::assertEquals('123456789', $purchaseRequestData['cres']);


        return $purchaseRequest;
    }

    /**
     * @depends testValidationPasses
     * @param CompletePurchaseRequest $purchaseRequest
     */
    public function testHttpMethodIsPatch(CompletePurchaseRequest $purchaseRequest): void
    {
        self::assertEquals(Request::METHOD_PATCH, $purchaseRequest->getHttpMethod());
    }

    /**
     * @depends testValidationPasses
     * @param CompletePurchaseRequest $purchaseRequest
     */
    public function testDataInstances(CompletePurchaseRequest $purchaseRequest): void
    {
        self::assertInstanceOf(CompletePurchaseResponse::class, $purchaseRequest->send());
    }
}
