<?php
declare(strict_types=1);

namespace Srkt\Amplitude;

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Srkt\Amplitude\Exception\ServerErrorException;
use Srkt\Amplitude\Exception\TooManyEventsException;
use Srkt\Amplitude\Exception\TooManyRequestsException;
use Srkt\Amplitude\Model\Event;
use Srkt\Amplitude\Model\UserIdentity;
use Srkt\Amplitude\Model\UserProperties;
use Srkt\Amplitude\Validation\EventValidator;
use Srkt\Amplitude\Exception\AmplitudeClientException;
use Srkt\Amplitude\Exception\InvalidEventException;
use Srkt\Amplitude\Exception\InvalidIdentityException;
use Srkt\Amplitude\Http\Client\GuzzlePsr18ClientAdapter;
use Srkt\Amplitude\Http\Request\GuzzleRequestFactory;
use Srkt\Amplitude\Http\Request\RequestFactoryInterface;
use Srkt\Amplitude\Validation\UserIdentityValidator;

class Client
{
    /** @var string|null */
    protected $userId;

    /** @var array */
    protected $httpClientOptions;

    /** @var RequestFactoryInterface */
    protected $requestFactory;

    /** @var ClientInterface */
    private $httpClient;

    /** @var EventValidator */
    private $eventValidator;

    /** @var UserIdentityValidator */
    private $identityValidator;

    /**
     * @param string $apiKey Your Amplitude account api key
     * @param array  $defaultHttpClientOptions Optional default Guzzle client options. Will not be used in case
     *                                         of custom http client from setHttpClient function
     */
    public function __construct(string $apiKey, array $defaultHttpClientOptions = [])
    {
        $this->httpClientOptions = $defaultHttpClientOptions;
        $this->requestFactory = new GuzzleRequestFactory($apiKey);
        $this->eventValidator = new EventValidator();
        $this->identityValidator = new UserIdentityValidator();
    }

    /**
     * You can call this method to use any kind of PSR-18 http client instead of Guzzle one
     *
     * @param ClientInterface $client
     * @return $this
     */
    public function setHttpClient(ClientInterface $client): self
    {
        $this->httpClient = $client;
        return $this;
    }

    /**
     * Log one identity
     *
     * @param UserIdentity $identity
     * @return ResponseInterface
     * @throws ClientExceptionInterface|InvalidIdentityException|AmplitudeClientException|TooManyRequestsException|TooManyEventsException|ServerErrorException
     */
    public function identifyUser(UserIdentity $identity): ResponseInterface
    {
        return $this->identifyUsers([$identity]);
    }

    /**
     * Log bunch of user identities in one request
     *
     * @param UserIdentity[] $identities
     * @return ResponseInterface
     * @throws ClientExceptionInterface|InvalidIdentityException|AmplitudeClientException|TooManyRequestsException|TooManyEventsException|ServerErrorException
     */
    public function identifyUsers(array $identities): ResponseInterface
    {
        foreach ($identities as $identity) {
            $validationResult = $this->identityValidator->validate($identity);
            if (false === $validationResult->isSuccess()) {
                throw new InvalidIdentityException($validationResult->getErrorMessage(), $identity);
            }
        }
        return $this->sendRequest(
            $this->requestFactory->createIdentificationRequest($identities)
        );
    }

    /**
     * Log one event
     *
     * @param Event $event
     * @return ResponseInterface
     * @throws ClientExceptionInterface|InvalidEventException|AmplitudeClientException|TooManyRequestsException|TooManyEventsException|ServerErrorException
     */
    public function logEvent(Event $event): ResponseInterface
    {
        return $this->logEvents([$event]);
    }

    /**
     * Log bunch of events in one request
     *
     * @param array $events
     * @return ResponseInterface
     * @throws ClientExceptionInterface|InvalidEventException|AmplitudeClientException|TooManyRequestsException|TooManyEventsException|ServerErrorException
     */
    public function logEvents(array $events): ResponseInterface
    {
        foreach ($events as $event) {
            $validationResult = $this->eventValidator->validate($event);
            if (false === $validationResult->isSuccess()) {
                throw new InvalidEventException($validationResult->getErrorMessage(), $event);
            }
        }
        return $this->sendRequest(
            $this->requestFactory->createEventsRequest($events)
        );
    }

    /**
     * Quick event (event+identity) logging. No data objects, only simple arrays
     *
     * @param string|null $userId
     * @param string      $eventType
     * @param array       $eventProperties
     * @param array       $userProperties
     * @param string|null $deviceId
     * @return ResponseInterface
     * @throws ClientExceptionInterface|InvalidEventException|AmplitudeClientException|TooManyRequestsException|TooManyEventsException|ServerErrorException
     */
    public function log(
        ?string $userId,
        string $eventType,
        array $eventProperties = [],
        array $userProperties = [],
        string $deviceId = null
    ): ResponseInterface {
        if (null === $userId && null !== $this->userId) {
            $userId = $this->userId;
        }

        return $this->logEvent(
            new Event(
                $userId, $eventType, $eventProperties, new UserProperties($userProperties), null, $deviceId
            )
        );
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws ClientExceptionInterface|InvalidEventException|AmplitudeClientException|TooManyRequestsException|TooManyEventsException|ServerErrorException
     */
    protected function sendRequest(RequestInterface $request): ResponseInterface
    {
        $response = $this->getHttpClient()->sendRequest($request);
        if (200 !== $response->getStatusCode()) {
            switch ($response->getStatusCode()) {
                case 400:
                    throw new AmplitudeClientException(
                        sprintf('Server response - Malformed request: %s', $response->getBody()->getContents())
                    );
                    break;
                case 413:
                    throw new TooManyEventsException(
                        sprintf('Server response - Too many events in request: %s', $response->getBody()->getContents())
                    );
                    break;
                case 429:
                    throw new TooManyRequestsException(
                        sprintf('Server response - Too many requests: %s', $response->getBody()->getContents())
                    );
                    break;
                case 500:
                case 502:
                case 503:
                case 504:
                    throw new ServerErrorException(
                        sprintf('Server error: %s', $response->getBody()->getContents())
                    );
                    break;
                default:
                    throw new AmplitudeClientException(
                        sprintf('Server error: %s', $response->getBody()->getContents())
                    );
                    break;
            }

        }
        return $response;
    }

    protected function getHttpClient(): ClientInterface
    {
        if (null === $this->httpClient) {
            $this->httpClient = $this->createHttpClient();
        }
        return $this->httpClient;
    }

    protected function createHttpClient(): ClientInterface
    {
        $options = [
            'debug' => false,
            'verify' => false,
            'decode_content' => true
        ];

        if ([] !== $this->httpClientOptions) {
            $options = array_merge($options, $this->httpClientOptions);
        }

        return new GuzzlePsr18ClientAdapter($options);
    }
}