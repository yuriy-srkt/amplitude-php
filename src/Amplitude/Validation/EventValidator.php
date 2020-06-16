<?php
declare(strict_types=1);

namespace Srkt\Amplitude\Validation;

use Srkt\Amplitude\Model\Event;

class EventValidator
{
    public function validate(Event $event): ValidationResult
    {
        if (false === $this->validateEventType($event)) {
            return new ValidationResult(false, 'Empty event type');
        }
        if (false === $this->validateUserIdAndDeviceId($event)) {
            return new ValidationResult(
                false,
                'Empty UserId and DeviceId. At least one of them has to be set'
            );
        }
        return new ValidationResult(true);
    }

    private function validateUserIdAndDeviceId(Event $event): bool
    {
        return (null !== $event->getUserId() || null !== $event->getDeviceId());
    }

    private function validateEventType(Event $event): bool
    {
        return !empty($event->getEventType());
    }
}