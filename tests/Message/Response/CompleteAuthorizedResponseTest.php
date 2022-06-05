<?php

namespace Omnipay\Cardinity\Tests\Message\Response;

use Omnipay\Cardinity\Helper\JsonHelper;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Cardinity\Message\Response\CompleteAuthorizedResponse;
use Symfony\Component\HttpFoundation\Response;

class CompleteAuthorizedResponseTest extends AbstractResponseTest
{
    protected const RESPONSE_CLASS_NAME = CompleteAuthorizedResponse::class;
    private const TRANSACTION_REFERENCE = '90095d47-11bb-468b-8764-fd4fbb49a9f9';

    public function testCompleteAuthorizedResponseIsSuccess(): void
    {
        $data = [
            'id' => self::TRANSACTION_REFERENCE,
            'type' => 'settlement',
            'status' => 'approved',
        ];

        $response = $this->getPurchaseResponse($data, [], Response::HTTP_CREATED);

        self::assertTrue($response->isSuccessful());
        self::assertTrue($response->isSettlement());
        self::assertEquals(Response::HTTP_CREATED, $response->getCode());
    }

    public function testCompleteAuthorizedResponseIsFailed(): void
    {
        $data = [
            'error' => 'Card is expired'
        ];

        $response = $this->getPurchaseResponse($data, [], Response::HTTP_PAYMENT_REQUIRED);

        self::assertFalse($response->isSuccessful());
        self::assertEquals($data['error'], $response->getError());
        self::assertEquals(Response::HTTP_PAYMENT_REQUIRED, $response->getCode());
    }

    private function getPurchaseResponse(array $data = [], array $headers = [], int $statusCode = 0): CompleteAuthorizedResponse
    {
        $requestInterfaceMock = $this->createMock(RequestInterface::class);

        return new CompleteAuthorizedResponse($requestInterfaceMock, JsonHelper::encode($data), $headers, $statusCode);
    }
}
