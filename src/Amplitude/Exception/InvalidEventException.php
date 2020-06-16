<?php
declare(strict_types=1);

namespace Srkt\Amplitude\Exception;

use Srkt\Amplitude\Model\Event;

class InvalidEventException extends AmplitudeClientException
{
    /** @var Event */
    protected $event;

    public function __construct($message, Event $event, $code = 0, \Throwable $previous = null)
    {
        $this->event = $event;
        parent::__construct($message, $code, $previous);
    }

    public function getEvent(): Event
    {
        return $this->event;
    }
}