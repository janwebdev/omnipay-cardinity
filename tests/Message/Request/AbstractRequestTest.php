<?php

namespace Omnipay\Cardinity\Tests\Message\Request;

use Omnipay\Common\Http\ClientInterface;
use Omnipay\Cardinity\Entity\Card;
use Omnipay\Cardinity\Entity\ThreeDS2Data;
use Omnipay\Cardinity\Entity\BrowserInfo;
use Omnipay\Cardinity\Message\AbstractRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

abstract class AbstractRequestTest extends TestCase
{
    protected function makeRequest(string $class): AbstractRequest
    {
        $clientMock = $this->createMock(ClientInterface::class);
        $httpRequestMock = $this->createMock(HttpRequest::class);
        $responseMock = $this->createMock(ResponseInterface::class);
        $streamLineMock = $this->createMock(StreamInterface::class);

        $streamLineMock
            ->method('getContents')
            ->willReturn('');

        $responseMock
            ->method('getBody')
            ->willReturn($streamLineMock);

        $responseMock
            ->method('getHeaders')
            ->willReturn([]);

        $responseMock
            ->method('getStatusCode')
            ->willReturn(0);

        $clientMock
            ->method('request')
            ->willReturn($responseMock);

        return (new $class($clientMock, $httpRequestMock))
            ->setConsumerKey(uniqid('', true))
            ->setConsumerSecret(uniqid('', true));
    }

    protected function createCardEntityMock(): Card
    {
        return $this->createMock(Card::class);
    }

    protected function createThreeDS2DataEntityMock(): ThreeDS2Data
    {
        return $this->createMock(ThreeDS2Data::class);
    }

	protected function createBrowserInfoEntityMock(): BrowserInfo
	{
		return $this->createMock(BrowserInfo::class);
	}
}