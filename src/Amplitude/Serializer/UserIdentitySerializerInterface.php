<?php
declare(strict_types=1);

namespace Srkt\Amplitude\Serializer;

use Srkt\Amplitude\Model\UserIdentity;

interface UserIdentitySerializerInterface
{
    /**
     * @param UserIdentity[] $identities
     * @return string
     */
    public function serializeMany(array $identities): string;
}