<?php
declare(strict_types=1);

namespace Srkt\Amplitude\Model;


class UserIdentity
{
    /** @var string|null */
    protected $userId;

    /** @var UserProperties|null */
    protected $userProperties;

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

    /** @var string|null */
    protected $paying;

    /** @var string|null */
    protected $startVersion;

    public function __construct(
        ?string $userId,
        UserProperties $userProperties = null,
        string $deviceId = null
    ) {
        $this->userId = $userId;
        $this->userProperties = $userProperties;
        $this->deviceId = $deviceId;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUserId(?string $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function getUserProperties(): ?UserProperties
    {
        return $this->userProperties;
    }

    public function setUserProperties(?UserProperties $userProperties): self
    {
        $this->userProperties = $userProperties;
        return $this;
    }

    public function getDeviceId(): ?string
    {
        return $this->deviceId;
    }

    public function setDeviceId(?string $deviceId): self
    {
        $this->deviceId = $deviceId;
        return $this;
    }

    public function getAppVersion(): ?string
    {
        return $this->appVersion;
    }

    public function setAppVersion(?string $appVersion): self
    {
        $this->appVersion = $appVersion;
        return $this;
    }

    public function getPlatformData(): ?PlatformData
    {
        return $this->platformData;
    }

    public function setPlatformData(?PlatformData $platformData): self
    {
        $this->platformData = $platformData;
        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;
        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $region): self
    {
        $this->region = $region;
        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;
        return $this;
    }

    public function getDma(): ?string
    {
        return $this->dma;
    }

    public function setDma(?string $dma): self
    {
        $this->dma = $dma;
        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(?string $language): self
    {
        $this->language = $language;
        return $this;
    }

    public function getPaying(): ?string
    {
        return $this->paying;
    }

    public function setPaying(?string $paying): self
    {
        $this->paying = $paying;
        return $this;
    }

    public function getStartVersion(): ?string
    {
        return $this->startVersion;
    }

    public function setStartVersion(?string $startVersion): self
    {
        $this->startVersion = $startVersion;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $data = [
            'user_id' => $this->userId,
            'device_id' => $this->deviceId,
            'app_version' => $this->appVersion,
            'country' => $this->country,
            'region' => $this->region,
            'city' => $this->city,
            'dma' => $this->dma,
            'language' => $this->language,
            'paying' => $this->paying,
            'start_version' => $this->startVersion,
        ];

        if (null !== $this->platformData) {
            $data += [
                'platform' => $this->platformData->getPlatform(),
                'os_name' => $this->platformData->getOsName(),
                'os_version' => $this->platformData->getOsVersion(),
                'device_brand' => $this->platformData->getDeviceBrand(),
                'device_manufacturer' => $this->platformData->getDeviceManufacturer(),
                'device_model' => $this->platformData->getDeviceModel(),
                'carrier' => $this->platformData->getCarrier(),
            ];
        }

        if (null !== $this->userProperties) {
            $data['user_properties'] = $this->userProperties->getProperties();
        }

        $data = array_filter($data);

        return $data;
    }

}