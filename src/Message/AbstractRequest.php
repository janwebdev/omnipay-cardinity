<?php

namespace Omnipay\Cardinity\Message;

use Omnipay\Cardinity\Helper\JsonHelper;
use Omnipay\Cardinity\Traits\ApiCredentialsTrait;
use Omnipay\Cardinity\Exception\RequestException;
use Omnipay\Common\Message\AbstractRequest as OmnipayAbstractRequest;
use Omnipay\Common\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Omnipay\Common\Exception\RuntimeException;
use Omnipay\Common\Helper;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractRequest extends OmnipayAbstractRequest
{
    use ApiCredentialsTrait;

    private const HTTP_ERRORS = [
        Response::HTTP_BAD_REQUEST,
        Response::HTTP_UNAUTHORIZED,
        Response::HTTP_PAYMENT_REQUIRED,
        Response::HTTP_FORBIDDEN,
        Response::HTTP_NOT_FOUND,
        Response::HTTP_METHOD_NOT_ALLOWED,
        Response::HTTP_NOT_ACCEPTABLE,
        Response::HTTP_UNSUPPORTED_MEDIA_TYPE,
        Response::HTTP_INTERNAL_SERVER_ERROR,
        Response::HTTP_SERVICE_UNAVAILABLE,
    ];

    private const ENDPOINT_URL = 'https://api.cardinity.com/v1';

    protected string $httpMethod = Request::METHOD_POST;

    protected function getEndpointUrl(): string
    {
        return sprintf(
            "%s/%s",
            self::ENDPOINT_URL,
            $this->getEndpointMethod()
        );
    }

    public function getHttpMethod(): string
    {
        return $this->httpMethod;
    }

    public function sendData($data): ResponseInterface
    {
        $response = $this->httpClient->request(
            $this->httpMethod,
            $this->getEndpointUrl(),
            $this->getHeaders(),
            JsonHelper::encode($data)
        );

        if (in_array($response->getStatusCode(), self::HTTP_ERRORS, true)) {
            $decoded = JsonHelper::decode($response->getBody()->getContents());
            $message = $decoded['title'] ?? "error";
            $status = $decoded['status'] ?? $response->getStatusCode();
            $description = $decoded['detail'] ?? "error";
            $errors = $decoded['errors'] ?? [];
            throw new RequestException($message, $status, $description, $errors);
        }

        return $this->createResponse(
            $response->getBody()->getContents(),
            $response->getHeaders(),
            $response->getStatusCode()
        );
    }

    abstract protected function createResponse(
        string $bodyContents,
        array $headers,
        int $statusCode
    ): ResponseInterface;

    public function initialize(array $parameters = []): AbstractRequest
    {
        if (null !== $this->response) {
            throw new RuntimeException('Request cannot be modified after it has been sent!');
        }

        if ($this->parameters === null) {
            $this->parameters = new ParameterBag();
        }

        Helper::initialize($this, $parameters);

        return $this;
    }

    /**
     * Endpoint method
     */
    abstract public function getEndpointMethod(): string;

    /**
     * Prepare header for each request
     */
    protected function getHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Authorization' => "Authorization: OAuth {" . $this->getAuth() . "}"
        ];
    }

    protected function getAuth(): string
    {
        return 'oauth_consumer_key="' . $this->getConsumerKey() . '",'
            . 'oauth_signature_method="HMAC-SHA1",'
            . 'oauth_timestamp="' . time() . '",'
            . 'oauth_nonce="' . md5(time()) . '",'
            . 'oauth_version="1.0",'
            . 'oauth_signature="' . hash_hmac('sha1', $this->getRequestDataAsString(), $this->getConsumerSecret()) . '"'
            ;
    }

    /**
     * Method for preparing request data as string
     */
    public function getRequestDataAsString(): string
    {
        return $this->getHttpMethod() . $this->getEndpointUrl();
    }
}
