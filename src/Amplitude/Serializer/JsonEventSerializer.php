<?php
declare(strict_types=1);

namespace Srkt\Amplitude\Serializer;

use Srkt\Amplitude\Model\Event;

class JsonEventSerializer implements EventSerializerInterface
{
    /** @inheritDoc */
    public function serializeMany(array $events): string
    {
        $normalizedEvents = [];
        foreach ($events as $event) {
            $normalizedEvents[] = $this->normalize($event);
        }

        if (1 === count($normalizedEvents)) {
            $normalizedEvents = reset($normalizedEvents);
        }

        return json_encode($normalizedEvents, JSON_FORCE_OBJECT);
    }

    protected function normalize(Event $event): array
    {
        $data = [
            'insert_id' => $event->getInsertId(),
            'event_id' => $event->getEventId(),
            'session_id' => $event->getSessionId(),
            'user_id' => $event->getUserId(),
            'device_id' => $event->getDeviceId(),
            'event_type' => $event->getEventType(),
            'time' => $event->getTime()->format('Uv'),
            'event_properties' => $event->getEventProperties(),
            'app_version' => $event->getAppVersion(),
            'country' => $event->getCountry(),
            'region' => $event->getRegion(),
            'city' => $event->getCity(),
            'dma' => $event->getDma(),
            'language' => $event->getLanguage(),
            'ip' => $event->getIp(),
            'idfa' => $event->getIdfa(),
            'adid' => $event->getAdid()
        ];

        $platformData = $event->getPlatformData();
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

        $revenueData = $event->getRevenueData();
        if (null !== $revenueData) {
            $data += [
                'price' => $revenueData->getPrice(),
                'quantity' => $revenueData->getQuantity(),
                'revenue' => $revenueData->getRevenue(),
                'revenueType' => $revenueData->getRevenueType(),
                'productId' => $revenueData->getProductId(),
            ];
        }

        $location = $event->getLocation();
        if (null !== $location) {
            $data += [
                'location_lat' => $location->getLat(),
                'location_lng' => $location->getLng(),
            ];
        }

        $userProperties = $event->getUserProperties();
        if (null !== $userProperties) {
            $data['user_properties'] = $userProperties->getProperties();
        }

        return array_filter($data);
    }
}