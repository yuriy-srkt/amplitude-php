<?php
declare(strict_types=1);

namespace Srkt\Amplitude\Tests;

use GuzzleHttp\Psr7\Response;
use Srkt\Amplitude\Client;
use Srkt\Amplitude\Exception\AmplitudeClientException;
use Srkt\Amplitude\Exception\ServerErrorException;
use Srkt\Amplitude\Exception\TooManyEventsException;
use Srkt\Amplitude\Exception\TooManyRequestsException;
use Srkt\Amplitude\Model\Event;
use Srkt\Amplitude\Model\PlatformData;
use Srkt\Amplitude\Model\RevenueData;
use Srkt\Amplitude\Model\UserProperties;
use Srkt\Amplitude\Exception\InvalidEventException;
use Srkt\Amplitude\Tests\Stub\HttpClientStub;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;

final class EventTest extends TestCase
{
    /** @var array */
    protected static $fullEventData;

    /** @var string */
    protected static $apiKey;

    public static function setUpBeforeClass(): void
    {
        self::$apiKey = 'api-key-for-test';
        self::$fullEventData = [
            'event_id' => time(),
            'session_id' => 123,
            'user_id' => '123',
            'device_id' => 'device-id-123',
            'event_type' => 'testEvent2',
            'time' => '1591897406687',
            'event_properties' => [
                'eventProperty1' => 1,
                'eventProperty2' => 'second_one',
            ],
            'app_version' => '1.0',
            'country' => 'Country',
            'region' => 'USA',
            'city' => 'City',
            'dma' => 'dma',
            'language' => 'en',
            'ip' => '192.168.0.1',
            'idfa' => 'idfa',
            'adid' => 'adid',
            'platform' => 'mobile',
            'os_name' => 'android',
            'os_version' => '5.6.5',
            'device_brand' => 'LG',
            'device_manufacturer' => 'LG man',
            'device_model' => 'Nexus5',
            'carrier' => 'carrier',
            'price' => 9.99,
            'quantity' => 1,
            'revenue' => 9.99,
            'revenueType' => 'buy',
            'productId' => 'Product1',
            'location_lat' => 0.534,
            'location_lng' => 0.4567,
            'user_properties' => [
                'userProperty1' => 11,
                'userProperty2' => 22,
            ],
        ];
    }

    public function testEmptyEventTypeValidation(): void
    {
        $event = new Event('1', '');
        $this->expectException(InvalidEventException::class);
        $this->createClient()->logEvent($event);

        /** @var InvalidEventException $exception */
        $exception = $this->getExpectedException();
        $this->assertSame($event, $exception->getEvent());
    }

    public function testShortEmptyEventTypeValidation(): void
    {
        $this->expectException(InvalidEventException::class);
        $this->createClient()->log(null, '', ['test' => 1], ['userName' => 123], 'testdevice');
    }

    public function testUserIdAndDeviceIdValidation(): void
    {
        $event = new Event(null, 'testEvent');
        $this->expectException(InvalidEventException::class);
        $this->createClient()->logEvent($event);

        /** @var InvalidEventException $exception */
        $exception = $this->getExpectedException();
        $this->assertSame($event, $exception->getEvent());
    }

    public function testShortUserIdAndDeviceIdValidation(): void
    {
        $this->expectException(InvalidEventException::class);
        $this->createClient()->log(null, 'testEvent');
    }

    public function testServerErrorsExceptions(): void
    {
        $httpClientStub = new HttpClientStub();

        $httpClientStub->setResponse(new Response(400));
        $this->expectException(AmplitudeClientException::class);
        $this->createClient($httpClientStub)->log('123', 'testEvent');

        $httpClientStub->setResponse(new Response(413));
        $this->expectException(TooManyEventsException::class);
        $this->createClient($httpClientStub)->log('123', 'testEvent');

        $httpClientStub->setResponse(new Response(429));
        $this->expectException(TooManyRequestsException::class);
        $this->createClient($httpClientStub)->log('123', 'testEvent');

        $httpClientStub->setResponse(new Response(500));
        $this->expectException(ServerErrorException::class);
        $this->createClient($httpClientStub)->log('123', 'testEvent');
    }

    public function testLogEventWithAllParams(): void
    {
        $httpClientStub = new HttpClientStub();
        $event = $this->createAllParamsEvent();
        $this->createClient($httpClientStub)->logEvent($event);

        $requestParams = $httpClientStub->getLastRequestBodyParams();

        $this->assertEquals(self::$apiKey, $requestParams['api_key']);
        $this->assertEquals(self::$fullEventData, json_decode($requestParams['event'], true));
    }

    public function testLogMultipleEventsWithAllParams(): void
    {
        $httpClientStub = new HttpClientStub();
        $events = [
            $this->createAllParamsEvent(1),
            $this->createAllParamsEvent(2),
            $this->createAllParamsEvent(3),
        ];
        $this->createClient($httpClientStub)->logEvents($events);

        $requestParams = $httpClientStub->getLastRequestBodyParams();

        $this->assertEquals(self::$apiKey, $requestParams['api_key']);
        $expectedEventsData = [
            array_merge(self::$fullEventData, ['event_id' => 1]),
            array_merge(self::$fullEventData, ['event_id' => 2]),
            array_merge(self::$fullEventData, ['event_id' => 3]),
        ];
        $this->assertEquals($expectedEventsData, json_decode($requestParams['event'], true));
    }

    public function testLogShortRequest(): void
    {
        $httpClientStub = new HttpClientStub();
        $client = $this->createClient($httpClientStub);
        $requestTime = new \DateTimeImmutable();
        $client->log(
            self::$fullEventData['user_id'],
            self::$fullEventData['event_type'],
            self::$fullEventData['event_properties'],
            self::$fullEventData['user_properties']
        );

        $requestParams = $httpClientStub->getLastRequestBodyParams();

        $this->assertEquals(self::$apiKey, $requestParams['api_key']);

        $expectedRequestData = array_intersect_key(
            self::$fullEventData,
            array_flip(['user_id', 'event_type', 'event_properties', 'user_properties'])
        ) + ['time' => $requestTime->format('Uv')];

        $this->assertEquals($expectedRequestData, json_decode($requestParams['event'], true));
    }

    public function testShortRequestNoUserId(): void
    {
        $httpClientStub = new HttpClientStub();
        $client = $this->createClient($httpClientStub);
        $requestTime = new \DateTimeImmutable();
        $client->log(
            null,
            self::$fullEventData['event_type'],
            self::$fullEventData['event_properties'],
            self::$fullEventData['user_properties'],
            self::$fullEventData['device_id']
        );

        $requestParams = $httpClientStub->getLastRequestBodyParams();
        $this->assertEquals(self::$apiKey, $requestParams['api_key']);

        $expectedRequestData = array_intersect_key(
            self::$fullEventData,
            array_flip(['event_type', 'event_properties', 'user_properties', 'device_id'])
        ) + ['time' => $requestTime->format('Uv')];

        $this->assertEquals($expectedRequestData, json_decode($requestParams['event'], true));
    }

    protected function createClient(ClientInterface $httpClient = null): Client
    {
        $client = new Client(self::$apiKey);
        if (null !== $httpClient) {
            $client->setHttpClient($httpClient);
        }
        return $client;
    }

    protected function createAllParamsEvent(int $eventId = null): Event
    {
        return (
            new Event(
                self::$fullEventData['user_id'],
                self::$fullEventData['event_type'],
                self::$fullEventData['event_properties'],
                new UserProperties(self::$fullEventData['user_properties'])
            )
        )
            ->setSessionId(self::$fullEventData['session_id'])
            ->setIp(self::$fullEventData['ip'])
            ->setDeviceId(self::$fullEventData['device_id'])
            ->setCountry(self::$fullEventData['country'])
            ->setCity(self::$fullEventData['city'])
            ->setAppVersion(self::$fullEventData['app_version'])
            ->setAdid(self::$fullEventData['adid'])
            ->setDma(self::$fullEventData['dma'])
            ->setEventId($eventId ?? self::$fullEventData['event_id'])
            ->setIdfa(self::$fullEventData['idfa'])
            ->setLanguage(self::$fullEventData['language'])
            ->setLocation(self::$fullEventData['location_lat'], self::$fullEventData['location_lng'])
            ->setTime(\DateTimeImmutable::createFromFormat('U.v', '' . (self::$fullEventData['time'] / 1000)))
            ->setPlatformData(
                new PlatformData(
                    self::$fullEventData['platform'],
                    self::$fullEventData['os_name'],
                    self::$fullEventData['os_version'],
                    self::$fullEventData['device_brand'],
                    self::$fullEventData['device_manufacturer'],
                    self::$fullEventData['device_model'],
                    self::$fullEventData['carrier']
                )
            )
            ->setRegion(self::$fullEventData['region'])
            ->setRevenueData(
                new RevenueData(
                    self::$fullEventData['revenue'],
                    self::$fullEventData['price'],
                    self::$fullEventData['quantity'],
                    self::$fullEventData['productId'],
                    self::$fullEventData['revenueType']
                )
            );
    }


}