<?php

namespace Omnipay\Cardinity\Tests;

use Omnipay\Cardinity\Exception\RequestException;
use Omnipay\Cardinity\Gateway;
use Omnipay\Cardinity\Message\Request\CompletePurchaseRequest;
use Omnipay\Cardinity\Message\Response\CompleteAuthorizedResponse;
use Omnipay\Cardinity\Message\Response\CompletePurchaseResponse;
use Omnipay\Cardinity\Message\Response\PurchaseResponse;
use Omnipay\Cardinity\Message\Response\RefundPurchaseResponse;
use Omnipay\Cardinity\Message\Response\ViewTransactionResponse;
use Omnipay\Cardinity\Message\Response\VoidResponse;
use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
	private const TRANSACTION_REFERENCE = '90095d47-11bb-468b-8764-fd4fbb49a9f9';
	private const EXCEPTION_MESSAGE = 'Validation Failed';
	private const DECLINE_ERROR_MESSAGE = 'Your card is not supported';
	private const SETTLEMENT_REFERENCE = '25e6f869-6675-4488-bd47-ccd298f74b3f';

	protected function setUp(): void
	{
		parent::setUp();

		$this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
	}

	public function testPurchaseApproved(): void
	{
		$options = $this->prepareOptions();

		$this->setMockHttpResponse('Response/PurchaseApproved.txt');

		$response = $this->gateway->purchase($options)->send();

		self::assertInstanceOf(PurchaseResponse::class, $response);
		self::assertTrue($response->isSuccessful());
		self::assertFalse($response->isRedirect());
		self::assertEquals(self::TRANSACTION_REFERENCE, $response->getTransactionReference());
	}

	public function testPurchasePending(): void
	{
		$options = $this->prepareOptions();

		$this->setMockHttpResponse('Response/PurchasePending.txt');

		$response = $this->gateway->purchase($options)->send();

		self::assertInstanceOf(PurchaseResponse::class, $response);
		self::assertTrue($response->isPending());
		self::assertTrue($response->isRedirect());
		self::assertEquals(self::TRANSACTION_REFERENCE, $response->getTransactionReference());
	}

	public function testPurchaseDeclined(): void
	{
		$options = $this->prepareOptions();

		$this->setMockHttpResponse('Response/PurchaseDeclined.txt');

		$response = $this->gateway->purchase($options)->send();

		self::assertInstanceOf(PurchaseResponse::class, $response);
		self::assertTrue($response->isDeclined());
		self::assertEquals(self::TRANSACTION_REFERENCE, $response->getTransactionReference());
		self::assertEquals(self::DECLINE_ERROR_MESSAGE, $response->getError());
	}

	public function testPurchaseFailure(): void
	{
		$options = $this->prepareOptions();

		$this->setMockHttpResponse('Response/PurchaseFailure.txt');

		$this->expectException(RequestException::class);
		$this->expectExceptionMessage(self::EXCEPTION_MESSAGE);
		$this->gateway->purchase($options)->send();
	}

	public function testCompletePurchaseSuccess(): void
	{
		$options = [
			'transactionReference' => self::TRANSACTION_REFERENCE,
			'type' => CompletePurchaseRequest::TYPE_3DSV2,
			'cres' => 'aoiybcoihasbdciasndcaisu'
		];

		$this->setMockHttpResponse('Response/CompletePurchaseSuccess.txt');

		$response = $this->gateway->completePurchase($options)->send();

		self::assertInstanceOf(CompletePurchaseResponse::class, $response);
		self::assertTrue($response->isSuccessful());
		self::assertEquals(self::TRANSACTION_REFERENCE, $response->getTransactionReference());
	}

	public function testFetchTransactionSuccess(): void
	{
		$options = [
			'transactionReference' => self::TRANSACTION_REFERENCE,
		];

		$this->setMockHttpResponse('Response/FetchPurchaseSuccess.txt');

		$response = $this->gateway->fetchTransaction($options)->send();
		self::assertInstanceOf(ViewTransactionResponse::class, $response);
		self::assertTrue($response->isSuccessful());
		self::assertEquals(self::TRANSACTION_REFERENCE, $response->getTransactionReference());
		self::assertIsArray($response->getCard());
		self::assertArrayHasKey('card_brand', $response->getCard());
		self::assertEquals('Visa', $response->getCard()['card_brand']);
		self::assertIsArray($response->get3DS2data());
		self::assertArrayHasKey('notification_url', $response->get3DS2data());
		self::assertArrayHasKey('browser_info', $response->get3DS2data());
		self::assertIsArray($response->get3DS2data()['browser_info']);
		self::assertArrayHasKey('user_agent', $response->get3DS2data()['browser_info']);
	}

	public function testAuthorizeSuccess(): void
	{
		$options = $this->prepareOptions();

		$this->setMockHttpResponse('Response/AuthorizationPending.txt');

		$response = $this->gateway->authorize($options)->send();

		self::assertInstanceOf(PurchaseResponse::class, $response);
		self::assertTrue($response->isAuthorized());
		self::assertEquals(self::TRANSACTION_REFERENCE, $response->getTransactionReference());
	}

	public function testCompleteAuthorizeSuccess(): void
	{
		$options = [
			'transactionReference' => self::TRANSACTION_REFERENCE,
			'amount' => '50.00',
			'description' => 'Lorem ipsum dolor sit amet'
		];

		$this->setMockHttpResponse('Response/AuthorizationApproved.txt');

		$response = $this->gateway->completeAuthorize($options)->send();

		self::assertInstanceOf(CompleteAuthorizedResponse::class, $response);
		self::assertTrue($response->isSuccessful());
		self::assertTrue($response->isSettlement());
		self::assertEquals(self::SETTLEMENT_REFERENCE, $response->getTransactionReference());
		self::assertEquals(self::TRANSACTION_REFERENCE, $response->getAuthorizedPaymentId());
	}

	public function testRefundSuccess(): void
	{
		$options = [
			'transactionReference' => self::TRANSACTION_REFERENCE,
			'amount' => '50.00',
			'description' => 'Lorem ipsum dolor sit amet'
		];

		$this->setMockHttpResponse('Response/RefundSuccess.txt');

		$response = $this->gateway->refund($options)->send();

		self::assertInstanceOf(RefundPurchaseResponse::class, $response);
		self::assertTrue($response->isSuccessful());
		self::assertTrue($response->isRefund());
		self::assertEquals(self::SETTLEMENT_REFERENCE, $response->getTransactionReference());
		self::assertEquals(self::TRANSACTION_REFERENCE, $response->getParentPurchaseId());
	}

	public function testVoidSuccess(): void
	{
		$options = [
			'transactionReference' => self::TRANSACTION_REFERENCE,
		];

		$this->setMockHttpResponse('Response/VoidSuccess.txt');

		$response = $this->gateway->void($options)->send();

		self::assertInstanceOf(VoidResponse::class, $response);
		self::assertTrue($response->isSuccessful());
		self::assertTrue($response->isVoid());
		self::assertEquals(self::SETTLEMENT_REFERENCE, $response->getTransactionReference());
		self::assertEquals(self::TRANSACTION_REFERENCE, $response->getParentPurchaseId());
	}

	private function prepareOptions(): array
	{
		return [
			"amount" => "50.00",
			"currency" => "EUR",
			"description" => "some description",
			"order_id" => "123456789",
			"country" => "LT",
			"payment_method" => "card",
			"payment_instrument" => [
				"pan" => "4111111111111111",
				"exp_year" => 2021,
				"exp_month" => 11,
				"cvc" => "999",
				"holder" => "John Doe"
			],
			"threeds2_data" => [
				"notification_url" => "https://www.myonlineshop.com/callback/3dsv2",
				"browser_info" => [
					"accept_header" => "text/html",
					"browser_language" => "en-US",
					"screen_width" => 1920,
					"screen_height" => 1040,
					"challenge_window_size" => "full-screen",
					"user_agent" => "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:21.0) Gecko/20100101 Firefox/21.0",
					"color_depth" => 24,
					"time_zone" => 3
				]
			],
			"statement_descriptor_suffix" => "Testing purchase payment",
			"callback_url" => "https://www.myonlineshop.com/callback/3dsv2"
		];
	}
}