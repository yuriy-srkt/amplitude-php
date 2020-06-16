<?php
declare(strict_types=1);

namespace Srkt\Amplitude\Validation;

use Srkt\Amplitude\Model\UserIdentity;

class UserIdentityValidator
{
    public function validate(UserIdentity $event): ValidationResult
    {
        if (false === $this->validateUserIdAndDeviceId($event)) {
            return new ValidationResult(
                false,
                'Empty UserId and DeviceId. At least one of them has to be set'
            );
        }
        return new ValidationResult(true);
    }

    private function validateUserIdAndDeviceId(UserIdentity $event): bool
    {
        return (null !== $event->getUserId() || null !== $event->getDeviceId());
    }
}