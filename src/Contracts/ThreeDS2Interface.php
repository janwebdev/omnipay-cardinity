<?php

namespace Omnipay\Cardinity\Contracts;

interface ThreeDS2Interface extends ToArrayInterface
{
    public function setNotificationUrl(string $url): self;
    public function getNotificationUrl(): string;
    public function setBrowserInfo(array $browserInfo): self;
    public function getBrowserInfo(): array;
}
