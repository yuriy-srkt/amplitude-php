<?php
declare(strict_types=1);

namespace Srkt\Amplitude\Validation;

class ValidationResult
{
    /** @var bool */
    private $isSuccess;

    /** @var null|string */
    private $errorMessage;

    public function __construct(bool $isSuccess, string $errorMessage = null)
    {
        $this->isSuccess = $isSuccess;
        $this->errorMessage = $errorMessage;
    }

    public function isSuccess(): bool
    {
        return $this->isSuccess;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

}