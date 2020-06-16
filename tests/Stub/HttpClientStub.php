<?php
declare(strict_types=1);

namespace Srkt\Amplitude\Tests\Stub;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HttpClientStub implements ClientInterface
{
    /** @var RequestInterface */
    private $lastRequest;

    /** @var ResponseInterface */
    private $response;

    public function __construct()
    {
        $this->response = new Response();
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $this->lastRequest = $request;
        return $this->response;
    }

    public function setResponse(ResponseInterface $response): self
    {
        $this->response = $response;
        return $this;
    }

    public function getLastRequest(): RequestInterface
    {
        return $this->lastRequest;
    }

    public function getLastRequestBodyParams(): array
    {
        $requestParams = [];
        parse_str(urldecode($this->lastRequest->getBody()->getContents()), $requestParams);
        return $requestParams;
    }

}