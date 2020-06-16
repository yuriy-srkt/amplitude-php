<?php
declare(strict_types=1);

namespace Srkt\Amplitude\Serializer;

use Srkt\Amplitude\Model\UserIdentity;

class JsonUserIdentitySerializer implements UserIdentitySerializerInterface
{
    /** @inheritDoc */
    public function serializeMany(array $identities): string
    {
        $normalizedIdentities = [];
        foreach ($identities as $identity) {
            $normalizedIdentities[] = $this->normalize($identity);
        }

        if (1 === count($normalizedIdentities)) {
            $normalizedIdentities = reset($normalizedIdentities);
        }

        return json_encode($normalizedIdentities, JSON_FORCE_OBJECT);
    }

    protected function normalize(UserIdentity $identity): array
    {
        $data = [
            'user_id' => $identity->getUserId(),
            'device_id' => $identity->getDeviceId(),
            'app_version' => $identity->getAppVersion(),
            'country' => $identity->getCountry(),
            'region' => $identity->getRegion(),
            'city' => $identity->getCity(),
            'dma' => $identity->getDma(),
            'language' => $identity->getLanguage(),
            'paying' => $identity->getPaying(),
            'start_version' => $identity->getStartVersion(),
        ];

        $platformData = $identity->getPlatformData();
        if (null !== $platformData) {
            $data += [
                'platform' => $platformData->getPlatform(),
                'os_name' => $platformData->getOsName(),
                'os_version' => $platformData->getOsVersion(),
                'device_brand' => $platformData->getDeviceBrand(),
                'device_manufacturer' => $platformData->getDeviceManufacturer(),
                'device_model' => $platformData->getDeviceModel(),
                'carrier' => $platformData->getCarrier(),
            ];
        }

        $userProperties = $identity->getUserProperties();
        if (null !== $userProperties) {
            $data['user_properties'] = $userProperties->getProperties();
        }

        return array_filter($data);
    }
}