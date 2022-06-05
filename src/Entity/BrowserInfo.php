<?php

namespace Omnipay\Cardinity\Entity;

use Omnipay\Cardinity\Contracts\BrowserInfoInterface;
use Omnipay\Common\ParametersTrait;

class BrowserInfo implements BrowserInfoInterface
{
    use ParametersTrait;

    public function __construct(array $parameters)
    {
        $this->initialize($parameters);
    }

    public function getAcceptHeader(): string
    {
        return $this->getParameter('accept_header');
    }

    public function setAcceptHeader(string $acceptHeader): self
    {
        return $this->setParameter('accept_header', $acceptHeader);
    }

    public function getBrowserLanguage(): string
    {
        return $this->getParameter('browser_language');
    }

    public function setBrowserLanguage(string $browserLanguage): self
    {
        return $this->setParameter('browser_language', $browserLanguage);
    }

    public function getScreenWidth(): int
    {
        return $this->getParameter('screen_width');
    }

    public function setScreenWidth(int $width): self
    {
        return $this->setParameter('screen_width', $width);
    }

    public function getScreenHeight(): int
    {
        return $this->getParameter('screen_height');
    }

    public function setScreenHeight(int $height): self
    {
        return $this->setParameter('screen_height', $height);
    }

    public function getChallengeWindowSize(): string
    {
        return $this->getParameter('challenge_window_size');
    }

    public function setChallengeWindowSize(string $windowSize): self
    {
        return $this->setParameter('challenge_window_size', $windowSize);
    }

    public function getUserAgent(): string
    {
        return $this->getParameter('user_agent');
    }

    public function setUserAgent(string $userAgent): self
    {
        return $this->setParameter('user_agent', $userAgent);
    }

    public function getColorDepth(): int
    {
        return $this->getParameter('color_depth');
    }

    public function setColorDepth(int $colorDepth): self
    {
        return $this->setParameter('color_depth', $colorDepth);
    }

    public function getTimeZone(): int
    {
        return $this->getParameter('time_zone');
    }

    public function setTimeZone(int $timeZone): self
    {
        return $this->setParameter('time_zone', $timeZone);
    }

    public function toArray(): array
    {
        return $this->getParameters();
    }
}
