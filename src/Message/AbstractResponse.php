<?php

namespace Omnipay\Cardinity\Message;

use Omnipay\Cardinity\Helper\JsonHelper;
use Omnipay\Common\Message\AbstractResponse as OmnipayAbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractResponse extends OmnipayAbstractResponse
{
    private array $headers;

    private int $statusCode;

    private ?string $callbackUrl;

    public function __construct(
        RequestInterface $request,
        string $data,
        array $headers,
        int $statusCode,
        ?string $callbackUrl = null
    ) {
        parent::__construct($request, $data);
        $this->headers = $headers;
        $this->statusCode = $statusCode;
        $this->callbackUrl = $callbackUrl;
    }

    public function getTransactionReference(): ?string
    {
        return $this->getValueFromData('id');
    }

    public function getDeclinedReason(): ?string
    {
        return $this->getValueFromData('error');
    }

    public function hasErrors(): bool
    {
        return null !== $this->getError();
    }

    public function getError(): ?string
    {
        return $this->getValueFromData('error');
    }

    public function getCode(): int
    {
        return $this->getStatusCode();
    }

    protected function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getCallbackUrl(): ?string
    {
        return $this->callbackUrl;
    }

    public function getMessage(): ?string
    {
        return $this->data;
    }

    protected function getHeaders(): array
    {
        return $this->headers;
    }

    protected function isStatusCodeCreated(): bool
    {
        return $this->statusCode === Response::HTTP_CREATED;
    }

    protected function isStatusCodePending(): bool
    {
        return $this->statusCode === Response::HTTP_ACCEPTED;
    }

    protected function isStatusNeedPayment(): bool
    {
        return $this->statusCode === Response::HTTP_PAYMENT_REQUIRED;
    }

    protected function getValueFromData(string $key, $default = null)
    {
        $data = $this->getData();
        return $data[$key] ?? $default;
    }

    public function getData(): array
    {
        return JsonHelper::decode($this->data);
    }
}
