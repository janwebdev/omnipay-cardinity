<?php

namespace Omnipay\Cardinity\Tests\Message\Request;

use Omnipay\Cardinity\Message\AbstractRequest;
use Omnipay\Cardinity\Message\Request\PurchaseRequest;
use Omnipay\Cardinity\Message\Response\PurchaseResponse;
use Omnipay\Common\Exception\InvalidRequestException;
use Symfony\Component\HttpFoundation\Request;

class PurchaseRequestTest extends AbstractRequestTest
{
    private AbstractRequest $request;

    protected function setUp(): void
    {
        $this->request = $this->makeRequest(PurchaseRequest::class);
    }

    public function testValidationFails(): void
    {
        $this->expectException(InvalidRequestException::class);

        $this->request->getData();
    }

    public function testValidationPasses(): AbstractRequest
    {
        $parameters = [
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

        $purchaseRequest = $this->request->initialize($parameters);
        $purchaseRequestData = $purchaseRequest->getData();

        self::assertArrayHasKey('amount', $purchaseRequestData);
        self::assertArrayHasKey('currency', $purchaseRequestData);
        self::assertArrayHasKey('description', $purchaseRequestData);
        self::assertArrayHasKey('order_id', $purchaseRequestData);
        self::assertArrayHasKey('country', $purchaseRequestData);
        self::assertArrayHasKey('settle', $purchaseRequestData);
        self::assertArrayHasKey('payment_method', $purchaseRequestData);
        self::assertArrayHasKey('payment_instrument', $purchaseRequestData);
        self::assertIsArray($purchaseRequestData['payment_instrument']);
        self::assertArrayHasKey('pan', $purchaseRequestData['payment_instrument']);
        self::assertArrayHasKey('exp_year', $purchaseRequestData['payment_instrument']);
        self::assertArrayHasKey('exp_month', $purchaseRequestData['payment_instrument']);
        self::assertArrayHasKey('cvc', $purchaseRequestData['payment_instrument']);
        self::assertArrayHasKey('holder', $purchaseRequestData['payment_instrument']);
        self::assertArrayHasKey('threeds2_data', $purchaseRequestData);
        self::assertIsArray($purchaseRequestData['threeds2_data']);
        self::assertArrayHasKey('notification_url', $purchaseRequestData['threeds2_data']);
        self::assertArrayHasKey('browser_info', $purchaseRequestData['threeds2_data']);
        self::assertIsArray($purchaseRequestData['threeds2_data']['browser_info']);
        self::assertArrayHasKey('accept_header', $purchaseRequestData['threeds2_data']['browser_info']);
        self::assertArrayHasKey('browser_language', $purchaseRequestData['threeds2_data']['browser_info']);
        self::assertArrayHasKey('screen_width', $purchaseRequestData['threeds2_data']['browser_info']);
        self::assertArrayHasKey('screen_height', $purchaseRequestData['threeds2_data']['browser_info']);
        self::assertArrayHasKey('challenge_window_size', $purchaseRequestData['threeds2_data']['browser_info']);
        self::assertArrayHasKey('user_agent', $purchaseRequestData['threeds2_data']['browser_info']);
        self::assertArrayHasKey('color_depth', $purchaseRequestData['threeds2_data']['browser_info']);
        self::assertArrayHasKey('time_zone', $purchaseRequestData['threeds2_data']['browser_info']);
        self::assertArrayHasKey('statement_descriptor_suffix', $purchaseRequestData);
        self::assertArrayHasKey('callback_url', $purchaseRequestData);

        return $purchaseRequest;
    }

    /**
     * @depends testValidationPasses
     * @param PurchaseRequest $purchaseRequest
     */
    public function testHttpMethodIsPost(PurchaseRequest $purchaseRequest): void
    {
        self::assertEquals(Request::METHOD_POST, $purchaseRequest->getHttpMethod());
    }

    /**
     * @depends testValidationPasses
     * @param PurchaseRequest $purchaseRequest
     */
    public function testDataInstances(PurchaseRequest $purchaseRequest): void
    {
        self::assertInstanceOf(PurchaseResponse::class, $purchaseRequest->send());
    }
}
