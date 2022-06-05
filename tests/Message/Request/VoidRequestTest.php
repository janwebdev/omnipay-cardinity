<?php

namespace Omnipay\Cardinity\Tests\Message\Request;

use Omnipay\Cardinity\Message\AbstractRequest;
use Omnipay\Cardinity\Message\Request\VoidRequest;
use Omnipay\Cardinity\Message\Response\VoidResponse;
use Omnipay\Common\Exception\InvalidRequestException;
use Symfony\Component\HttpFoundation\Request;

class VoidRequestTest extends AbstractRequestTest
{
    private AbstractRequest $request;

    private const TRANSACTION_REFERENCE = '90095d47-11bb-468b-8764-fd4fbb49a9f9';
    private const DESCRIPTION = 'Lorem ipdum dolor sit amet';

    protected function setUp(): void
    {
        $this->request = $this->makeRequest(VoidRequest::class);
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
            'description' => self::DESCRIPTION,
        ];

        $purchaseRequest = $this->request->initialize($parameters);
        $purchaseRequestData = $purchaseRequest->getData();

        self::assertArrayHasKey('description', $purchaseRequestData);
        self::assertEquals(self::DESCRIPTION, $purchaseRequestData['description']);


        return $purchaseRequest;
    }

    /**
     * @depends testValidationPasses
     * @param VoidRequest $purchaseRequest
     */
    public function testHttpMethodIsPost(VoidRequest $purchaseRequest): void
    {
        self::assertEquals(Request::METHOD_POST, $purchaseRequest->getHttpMethod());
    }

    /**
     * @depends testValidationPasses
     * @param VoidRequest $purchaseRequest
     */
    public function testDataInstances(VoidRequest $purchaseRequest): void
    {
        self::assertInstanceOf(VoidResponse::class, $purchaseRequest->send());
    }
}
