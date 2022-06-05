<?php

namespace Omnipay\Cardinity\Contracts;

interface BrowserInfoInterface extends ToArrayInterface
{
    public function getAcceptHeader(): string;
    public function setAcceptHeader(string $acceptHeader): self;
    public function getBrowserLanguage(): string;
    public function setBrowserLanguage(string $browserLanguage): self;
    public function getScreenWidth(): int;
    public function setScreenWidth(int $width): self;
    public function getScreenHeight(): int;
    public function setScreenHeight(int $height): self;
    public function getChallengeWindowSize(): string;
    public function setChallengeWindowSize(string $windowSize): self;
    public function getUserAgent(): string;
    public function setUserAgent(string $userAgent): self;
    public function getColorDepth(): int;
    public function setColorDepth(int $colorDepth): self;
    public function getTimeZone(): int;
    public function setTimeZone(int $timeZone): self;
}
