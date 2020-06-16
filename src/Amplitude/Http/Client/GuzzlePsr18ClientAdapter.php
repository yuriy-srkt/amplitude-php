<?php
declare(strict_types=1);

namespace Srkt\Amplitude\Http\Client;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Adapter is used for Guzzle versions < 7.0, due to lack of Psr Client interface support in it
 */
class GuzzlePsr18ClientAdapter extends Client implements ClientInterface
{
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $options[RequestOptions::SYNCHRONOUS] = true;
        $options[RequestOptions::ALLOW_REDIRECTS] = false;
        $options[RequestOptions::HTTP_ERRORS] = false;

        return $this->sendAsync($request, $options)->wait();
    }
}