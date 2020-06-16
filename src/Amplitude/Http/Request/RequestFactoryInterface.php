<?php
declare(strict_types=1);

namespace Srkt\Amplitude\Http\Request;

use Psr\Http\Message\RequestInterface;
use Srkt\Amplitude\Model\Event;
use Srkt\Amplitude\Model\UserIdentity;

interface RequestFactoryInterface
{
    public const EVENT_URI = '/httpapi';
    public const IDENTIFY_URI = '/identify';
    public const AMPLITUDE_BASE_URL = 'https://api.amplitude.com';

    /**
     * @param Event[] $events
     * @return RequestInterface
     */
    public function createEventsRequest(array $events): RequestInterface;

    /**
     * @param UserIdentity[] $identities
     * @return RequestInterface
     */
    public function createIdentificationRequest(array $identities): RequestInterface;
}