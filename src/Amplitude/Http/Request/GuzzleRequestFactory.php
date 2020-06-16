<?php
declare(strict_types=1);

namespace Srkt\Amplitude\Http\Request;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Srkt\Amplitude\Model\Event;
use Srkt\Amplitude\Model\UserIdentity;
use Srkt\Amplitude\Serializer\EventSerializerInterface;
use Srkt\Amplitude\Serializer\JsonEventSerializer;
use Srkt\Amplitude\Serializer\JsonUserIdentitySerializer;
use Srkt\Amplitude\Serializer\UserIdentitySerializerInterface;

class GuzzleRequestFactory implements RequestFactoryInterface
{
    /** @var string */
    protected $apiKey;

    /** @var EventSerializerInterface */
    private $eventSerializer;

    /** @var UserIdentitySerializerInterface */
    private $identitySerializer;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->eventSerializer = new JsonEventSerializer();
        $this->identitySerializer = new JsonUserIdentitySerializer();
    }

    /**
     * @param Event[] $events
     * @return RequestInterface
     */
    public function createEventsRequest(array $events): RequestInterface
    {
        return new Request(
            'POST',
            self::AMPLITUDE_BASE_URL . self::EVENT_URI,
            ['Accept-Encoding' => 'gzip'],
            http_build_query(
                [
                    'api_key' => $this->apiKey,
                    'event' => $this->eventSerializer->serializeMany($events),
                ]
            )
        );
    }

    /**
     * @param UserIdentity[] $identities
     * @return RequestInterface
     */
    public function createIdentificationRequest(array $identities): RequestInterface
    {
        return new Request(
            'POST',
            self::AMPLITUDE_BASE_URL . self::IDENTIFY_URI,
            ['Accept-Encoding' => 'gzip'],
            http_build_query(
                [
                    'api_key' => $this->apiKey,
                    'identification' => $this->identitySerializer->serializeMany($identities),
                ]
            )
        );
    }
}