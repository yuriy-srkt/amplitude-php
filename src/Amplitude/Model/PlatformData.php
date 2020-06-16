<?php
declare(strict_types=1);

namespace Srkt\Amplitude\Model;

class PlatformData
{
    /** @var string|null */
    private $platform;

    /** @var string|null */
    private $osName;

    /** @var string|null */
    private $osVersion;

    /** @var string|null */
    private $deviceBrand;

    /** @var string|null */
    private $deviceManufacturer;

    /** @var string|null */
    private $deviceModel;

    /** @var string|null */
    private $carrier;

    /**
     * @param string|null $platform
     * @param string|null $osName
     * @param string|null $osVersion
     * @param string|null $deviceBrand
     * @param string|null $deviceManufacturer
     * @param string|null $deviceModel
     * @param string|null $carrier
     */
    public function __construct(
        string $platform = null,
        string $osName = null,
        string $osVersion = null,
        string $deviceBrand = null,
        string $deviceManufacturer = null,
        string $deviceModel = null,
        string $carrier = null
    ) {
        $this->platform = $platform;
        $this->osName = $osName;
        $this->osVersion = $osVersion;
        $this->deviceBrand = $deviceBrand;
        $this->deviceManufacturer = $deviceManufacturer;
        $this->deviceModel = $deviceModel;
        $this->carrier = $carrier;
    }

    public function getPlatform(): ?string
    {
        return $this->platform;
    }

    public function getOsName(): ?string
    {
        return $this->osName;
    }

    public function getOsVersion(): ?string
    {
        return $this->osVersion;
    }

    public function getDeviceBrand(): ?string
    {
        return $this->deviceBrand;
    }

    public function getDeviceManufacturer(): ?string
    {
        return $this->deviceManufacturer;
    }

    public function getDeviceModel(): ?string
    {
        return $this->deviceModel;
    }

    public function getCarrier(): ?string
    {
        return $this->carrier;
    }

}