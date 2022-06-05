<?php

namespace Omnipay\Cardinity\Exception;

class RequestException extends \Exception
{
    private string $description;
    private ?array $errors;

    public function __construct(string $message, int $code, string $description, array $errors)
    {
        $this->description = $description;
        $this->errors = $errors;
        parent::__construct($message, $code);
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
