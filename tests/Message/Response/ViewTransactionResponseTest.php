<?php

namespace Omnipay\Cardinity\Tests\Message\Response;

use Omnipay\Cardinity\Helper\JsonHelper;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Cardinity\Message\Response\ViewTransactionResponse;
use Symfony\Component\HttpFoundation\Response;

class ViewTransactionResponseTest extends AbstractResponseTest
{
    protected const RESPONSE_CLASS_NAME = ViewTransactionResponse::class;

    public function testViewTransactionResponseIsApproved(): void
    {
        $data = [
            "id" => "90095d47-11bb-468b-8764-fd4fbb49a9f9",
            "amount" => "15.00",
            "currency" => "EUR",
            "created" => "2014-12-19T11:52:53Z",
            "type" => "authorization",
            "live" => true,
            "status" => "approved",
            "order_id" => "123456",
            "description" => "some description",
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
            'statement_descriptor_suffix' => 'Lorem ipsum dolor sit amet'
        ];

        $response = $this->getPurchaseResponse($data, [], Response::HTTP_OK);

        self::assertEquals(Response::HTTP_OK, $response->getCode());
        self::assertEquals($data['id'], $response->getTransactionReference());
        self::assertEquals($data['amount'], $response->getAmount());
        self::assertEquals($data['currency'], $response->getCurrency());
        self::assertInstanceOf(\DateTime::class, $response->getCreatedAt());
        self::assertEquals($data['type'], $response->getType());
        self::assertTrue($response->isReal());
        self::assertEquals($data['status'], $response->getStatus());
        self::assertEquals($data['order_id'], $response->getOrderId());
        self::assertEquals($data['description'], $response->getDescription());
        self::assertEquals($data['country'], $response->getCountry());
        self::assertIsArray($response->getCard());
        self::assertIsArray($response->get3DS2data());
        self::assertEquals($data['statement_descriptor_suffix'], $response->getStatement());
    }

    public function testViewTransactionResponseIsFailed(): void
    {
        $response = $this->getPurchaseResponse([], [], Response::HTTP_NOT_FOUND);

        self::assertEquals(Response::HTTP_NOT_FOUND, $response->getCode());
    }

    private function getPurchaseResponse(array $data = [], array $headers = [], int $statusCode = 0): ViewTransactionResponse
    {
        $requestInterfaceMock = $this->createMock(RequestInterface::class);

        return new ViewTransactionResponse($requestInterfaceMock, JsonHelper::encode($data), $headers, $statusCode);
    }
}
