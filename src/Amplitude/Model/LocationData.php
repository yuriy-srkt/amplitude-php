<?php
declare(strict_types=1);

namespace Srkt\Amplitude\Model;

class LocationData
{
    /** @var float */
    protected $lat;

    /** @var float */
    protected $lng;

    public function __construct(float $lat, float $lng)
    {
        $this->lat = $lat;
        $this->lng = $lng;
    }

    public function getLat(): float
    {
        return $this->lat;
    }

    public function getLng(): float
    {
        return $this->lng;
    }

}