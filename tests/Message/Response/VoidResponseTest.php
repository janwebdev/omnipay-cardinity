<?php

namespace Omnipay\Cardinity\Tests\Message\Response;

use Omnipay\Cardinity\Helper\JsonHelper;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Cardinity\Message\Response\VoidResponse;
use Symfony\Component\HttpFoundation\Response;

class VoidResponseTest extends AbstractResponseTest
{
	protected const RESPONSE_CLASS_NAME = VoidResponse::class;
	private const TRANSACTION_REFERENCE = '90095d47-11bb-468b-8764-fd4fbb49a9f9';
	private const SETTLEMENT_REFERENCE = '25e6f869-6675-4488-bd47-ccd298f74b3f';

	public function testCompleteAuthorizedResponseIsSuccess(): void
	{
		$data = [
			'id' => self::TRANSACTION_REFERENCE,
			'parent_id' => self::SETTLEMENT_REFERENCE,
			'type' => 'void',
			'status' => 'approved',
		];

		$response = $this->getPurchaseResponse($data, [], Response::HTTP_CREATED);

		self::assertTrue($response->isSuccessful());
		self::assertTrue($response->isVoid());
		self::assertEquals(self::SETTLEMENT_REFERENCE, $response->getParentPurchaseId());
		self::assertEquals(Response::HTTP_CREATED, $response->getCode());
	}

	public function testCompleteAuthorizedResponseIsFailed(): void
	{
		$data = [
			'status' => 'declined',
		];

		$response = $this->getPurchaseResponse($data, [], Response::HTTP_PAYMENT_REQUIRED);

		self::assertTrue($response->isDeclined());
		self::assertEquals(Response::HTTP_PAYMENT_REQUIRED, $response->getCode());
	}

	private function getPurchaseResponse(array $data = [], array $headers = [], int $statusCode = 0): VoidResponse
	{
		$requestInterfaceMock = $this->createMock(RequestInterface::class);

		return new VoidResponse($requestInterfaceMock, JsonHelper::encode($data), $headers, $statusCode);
	}
}