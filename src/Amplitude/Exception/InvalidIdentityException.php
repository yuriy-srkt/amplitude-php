<?php
declare(strict_types=1);

namespace Srkt\Amplitude\Exception;

use Srkt\Amplitude\Model\UserIdentity;

class InvalidIdentityException extends AmplitudeClientException
{
    /** @var UserIdentity */
    protected $identity;

    public function __construct($message, UserIdentity $identity, $code = 0, \Throwable $previous = null)
    {
        $this->identity = $identity;
        parent::__construct($message, $code, $previous);
    }

    public function getIdentity(): UserIdentity
    {
        return $this->identity;
    }
}