<?php

namespace Omnipay\Cardinity\Tests\Message\Response;

use Omnipay\Cardinity\Message\AbstractResponse;
use PHPUnit\Framework\TestCase;

abstract class AbstractResponseTest extends TestCase
{
    protected const RESPONSE_CLASS_NAME = AbstractResponse::class;

    public function testWhenJsonExceptionThrown(): void
    {
        $purchaseResponseStub = $this->createMock(static::RESPONSE_CLASS_NAME);

        $purchaseResponseStub->method('isSuccessful')
            ->willThrowException(new \JsonException());

        $this->expectException(\JsonException::class);

        $purchaseResponseStub->isSuccessful();
    }
}
