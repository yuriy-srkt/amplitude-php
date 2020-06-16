<?php
declare(strict_types=1);

namespace Srkt\Amplitude\Serializer;

use Srkt\Amplitude\Model\Event;

interface EventSerializerInterface
{
    /**
     * @param Event[] $events
     * @return string
     */
    public function serializeMany(array $events): string;
}