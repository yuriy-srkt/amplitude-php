<?php
declare(strict_types=1);

namespace Srkt\Amplitude\Model;


class UserProperties
{
    /**
     * @var array
     */
    protected $properties;

    /**
     * @param array $properties
     */
    public function __construct(array $properties = [])
    {
        $this->properties = $properties;
    }

    /**
     * @param string $key
     * @param mixed  $value
     * @return $this
     */
    public function set(string $key, $value)
    {
        $this->properties[$key] = $value;
        return $this;
    }

    /**
     * @param string $key
     * @return $this
     */
    public function remove(string $key)
    {
        unset($this->properties[$key]);
        return $this;
    }

    /**
     * @return array
     */
    public function getProperties(): array
    {
        return array_filter($this->properties);
    }
}