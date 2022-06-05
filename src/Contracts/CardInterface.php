<?php

namespace Omnipay\Cardinity\Contracts;

interface CardInterface extends ToArrayInterface
{
    public function setPan(string $pan): self;
    public function getPan(): string;
    public function setExpYear(string $expYear): self;
    public function getExpYear(): string;
    public function setExpMonth(string $expMonth): self;
    public function getExpMonth(): string;
    public function setCvc(string $cvc): self;
    public function getCvc(): string;
    public function setHolder(string $holder): self;
    public function getHolder(): string;
}
