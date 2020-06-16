<?php
declare(strict_types=1);

namespace Srkt\Amplitude\Model;


use DateTimeInterface;

class Event
{
    /** @var string|null */
    protected $userId;

    /** @var string */
    protected $eventType;

    /** @var array */
    protected $eventProperties = [];

    /** @var UserProperties|null */
    protected $userProperties;

    /** @var DateTimeInterface|null */
    protected $time;

    /** @var string|null */
    protected $deviceId;

    /** @var string|null */
    protected $appVersion;

    /** @var PlatformData|null */
    protected $platformData;

    /** @var string|null */
    protected $country;

    /** @var string|null */
    protected $region;

    /** @var string|null */
    protected $city;

    /** @var string|null */
    protected $dma;

    /** @var string|null */
    protected $language;

    /** @var RevenueData|null */
    protected $revenueData;

    /** @var LocationData|null */
    protected $location;

    /** @var string|null */
    protected $ip;

    /** @var string|null */
    protected $idfa;

    /** @var string|null */
    protected $adid;

    /** @var integer|null */
    protected $insertId;

    /** @var integer|null */
    protected $eventId;

    /** @var integer|null */
    protected $sessionId;

    public function __construct(
        ?string $userId,
        string $eventType,
        array $eventProperties = [],
        UserProperties $userProperties = null,
        DateTimeInterface $time = null,
        string $deviceId = null
    ) {
        $this->userId = $userId;
        $this->eventType = $eventType;
        $this->eventProperties = $eventProperties;
        $this->userProperties = $userProperties;
        $this->time = $time ?? new \DateTimeImmutable();
        $this->deviceId = $deviceId;
    }

    public function setPlatformData(?PlatformData $platformData): self
    {
        $this->platformData = $platformData;
        return $this;
    }

    public function setRevenueData(?RevenueData $revenueData): self
    {
        $this->revenueData = $revenueData;
        return $this;
    }

    public function setUserId(?string $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function setEventType(string $eventType): self
    {
        $this->eventType = $eventType;
        return $this;
    }

    public function setEventProperties(array $eventProperties): self
    {
        $this->eventProperties = $eventProperties;
        return $this;
    }

    public function setUserProperties(?UserProperties $userProperties): self
    {
        $this->userProperties = $userProperties;
        return $this;
    }

    public function setTime(?DateTimeInterface $time): self
    {
        $this->time = $time;
        return $this;
    }

    public function setDeviceId(?string $deviceId): self
    {
        $this->deviceId = $deviceId;
        return $this;
    }

    public function setAppVersion(?string $appVersion): self
    {
        $this->appVersion = $appVersion;
        return $this;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;
        return $this;
    }

    public function setRegion(?string $region): self
    {
        $this->region = $region;
        return $this;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;
        return $this;
    }

    public function setDma(?string $dma): self
    {
        $this->dma = $dma;
        return $this;
    }

    public function setLanguage(?string $language): self
    {
        $this->language = $language;
        return $this;
    }

    public function setLocation(float $lat, float $lng): self
    {
        $this->location = new LocationData($lat, $lng);
        return $this;
    }

    public function setIp(?string $ip): self
    {
        $this->ip = $ip;
        return $this;
    }

    public function setIdfa(?string $idfa): self
    {
        $this->idfa = $idfa;
        return $this;
    }

    public function setAdid(?string $adid): self
    {
        $this->adid = $adid;
        return $this;
    }

    public function setInsertId(?int $insertId): self
    {
        $this->insertId = $insertId;
        return $this;
    }

    public function setEventId(?int $eventId): self
    {
        $this->eventId = $eventId;
        return $this;
    }

    public function setSessionId(?int $sessionId): self
    {
        $this->sessionId = $sessionId;
        return $this;
    }

    public function setEventProperty(string $name, $value): self
    {
        $this->eventProperties[$name] = $value;
        return $this;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function getEventType(): string
    {
        return $this->eventType;
    }

    public function getEventProperties(): array
    {
        return $this->eventProperties;
    }

    public function getUserProperties(): ?UserProperties
    {
        return $this->userProperties;
    }

    public function getTime(): DateTimeInterface
    {
        return $this->time;
    }

    public function getDeviceId(): ?string
    {
        return $this->deviceId;
    }

    public function getAppVersion(): ?string
    {
        return $this->appVersion;
    }

    public function getPlatformData(): ?PlatformData
    {
        return $this->platformData;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function getDma(): ?string
    {
        return $this->dma;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function getRevenueData(): ?RevenueData
    {
        return $this->revenueData;
    }

    public function getLocation(): ?LocationData
    {
        return $this->location;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function getIdfa(): ?string
    {
        return $this->idfa;
    }

    public function getAdid(): ?string
    {
        return $this->adid;
    }

    public function getInsertId(): ?int
    {
        return $this->insertId;
    }

    public function getEventId(): ?int
    {
        return $this->eventId;
    }

    public function getSessionId(): ?int
    {
        return $this->sessionId;
    }

}