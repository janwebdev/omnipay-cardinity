<?php

namespace Omnipay\Cardinity\Tests\Message\Response;

use Omnipay\Cardinity\Helper\JsonHelper;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Cardinity\Message\Response\PurchaseResponse;
use Symfony\Component\HttpFoundation\Response;

class PurchaseResponseTest extends AbstractResponseTest
{
    protected const RESPONSE_CLASS_NAME = PurchaseResponse::class;

    public function testPurchaseResponseIsApproved(): void
    {
        $data = [
            'status' => 'approved'
        ];

        $purchaseResponse = $this->getPurchaseResponse($data, [], Response::HTTP_CREATED);

        self::assertTrue($purchaseResponse->isSuccessful());
        self::assertEquals(Response::HTTP_CREATED, $purchaseResponse->getCode());
    }

    public function testPurchaseResponseIsPending(): void
    {
        $data = [
            'status' => 'pending'
        ];

        $purchaseResponse = $this->getPurchaseResponse($data, [], Response::HTTP_ACCEPTED);

        self::assertTrue($purchaseResponse->isPending());
        self::assertEquals(Response::HTTP_ACCEPTED, $purchaseResponse->getCode());
    }

    public function testPurchaseResponseIsFailed(): void
    {
        $purchaseResponse = $this->getPurchaseResponse([], [], Response::HTTP_BAD_REQUEST);

        self::assertFalse($purchaseResponse->isSuccessful());
        self::assertEquals(Response::HTTP_BAD_REQUEST, $purchaseResponse->getCode());
    }

    private function getPurchaseResponse(array $data = [], array $headers = [], int $statusCode = 0): PurchaseResponse
    {
        $requestInterfaceMock = $this->createMock(RequestInterface::class);

        return new PurchaseResponse($requestInterfaceMock, JsonHelper::encode($data), $headers, $statusCode);
    }
}
