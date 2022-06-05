<?php

namespace Omnipay\Cardinity\Tests\Message\Response;

use Omnipay\Cardinity\Helper\JsonHelper;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Cardinity\Message\Response\CompletePurchaseResponse;
use Symfony\Component\HttpFoundation\Response;

class CompletePurchaseResponseTest extends AbstractResponseTest
{
    protected const RESPONSE_CLASS_NAME = CompletePurchaseResponse::class;
    private const TRANSACTION_REFERENCE = '90095d47-11bb-468b-8764-fd4fbb49a9f9';

    public function testCompletePurchaseResponseIsSuccess(): void
    {
        $data = [
            'status' => 'approved',
            'id' => self::TRANSACTION_REFERENCE
        ];

        $purchaseResponse = $this->getPurchaseResponse($data, [], Response::HTTP_CREATED);

        self::assertTrue($purchaseResponse->isSuccessful());
        self::assertEquals(Response::HTTP_CREATED, $purchaseResponse->getCode());
    }

    public function testCompletePurchaseResponseIsFailed(): void
    {
        $purchaseResponse = $this->getPurchaseResponse([], [], Response::HTTP_BAD_REQUEST);

        self::assertFalse($purchaseResponse->isSuccessful());
        self::assertEquals(Response::HTTP_BAD_REQUEST, $purchaseResponse->getCode());
    }

    private function getPurchaseResponse(array $data = [], array $headers = [], int $statusCode = 0): CompletePurchaseResponse
    {
        $requestInterfaceMock = $this->createMock(RequestInterface::class);

        return new CompletePurchaseResponse($requestInterfaceMock, JsonHelper::encode($data), $headers, $statusCode);
    }
}
