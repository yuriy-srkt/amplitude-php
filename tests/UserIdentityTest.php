<?php
declare(strict_types=1);

namespace Srkt\Amplitude\Tests;

use Srkt\Amplitude\Client;
use Srkt\Amplitude\Model\PlatformData;
use Srkt\Amplitude\Model\UserIdentity;
use Srkt\Amplitude\Model\UserProperties;
use Srkt\Amplitude\Exception\InvalidIdentityException;
use Srkt\Amplitude\Tests\Stub\HttpClientStub;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;

final class UserIdentityTest extends TestCase
{
    /** @var array */
    protected static $fullIdentityData;

    /** @var string */
    protected static $apiKey;

    public static function setUpBeforeClass(): void
    {
        self::$apiKey = 'api-key-for-test';
        self::$fullIdentityData = [
            'user_id' => '123',
            'device_id' => 'device-id-123',
            'app_version' => '1.0',
            'country' => 'Country',
            'region' => 'USA',
            'city' => 'City',
            'dma' => 'dma',
            'language' => 'en',
            'platform' => 'mobile',
            'os_name' => 'android',
            'os_version' => '5.6.5',
            'device_brand' => 'LG',
            'device_manufacturer' => 'LG man',
            'device_model' => 'Nexus5',
            'carrier' => 'carrier',
            'user_properties' => [
                'userProperty1' => 11,
                'userProperty2' => 22,
            ],
            'paying' => 'paying12',
            'start_version' => 'v1',
        ];
    }

    public function testIdentityValidation(): void
    {
        $identity = new UserIdentity(null, new UserProperties(self::$fullIdentityData['user_properties']));
        $this->expectException(InvalidIdentityException::class);
        $this->createClient()->identifyUser($identity);

        /** @var InvalidIdentityException $exception */
        $exception = $this->getExpectedException();
        $this->assertSame($identity, $exception->getIdentity());
    }

    public function testIdentifyUserWithAllParams(): void
    {
        $httpClientStub = new HttpClientStub();
        $identity = $this->createAllParamsIdentity();
        $this->createClient($httpClientStub)->identifyUser($identity);

        $requestParams = $httpClientStub->getLastRequestBodyParams();

        $this->assertEquals(self::$apiKey, $requestParams['api_key']);
        $this->assertEquals(self::$fullIdentityData, json_decode($requestParams['identification'], true));
    }

    public function testIdentifyMultipleUsersWithAllParams(): void
    {
        $httpClientStub = new HttpClientStub();
        $identities = [
            $this->createAllParamsIdentity('1'),
            $this->createAllParamsIdentity('2'),
            $this->createAllParamsIdentity('3'),
        ];
        $this->createClient($httpClientStub)->identifyUsers($identities);

        $requestParams = $httpClientStub->getLastRequestBodyParams();

        $this->assertEquals(self::$apiKey, $requestParams['api_key']);

        $expectedIdentitiesData = [
            array_merge(self::$fullIdentityData, ['user_id' => 1]),
            array_merge(self::$fullIdentityData, ['user_id' => 2]),
            array_merge(self::$fullIdentityData, ['user_id' => 3]),
        ];
        $this->assertEquals($expectedIdentitiesData, json_decode($requestParams['identification'], true));
    }

    public function testIdentifyUserWithNoUserId(): void
    {
        $identity = new UserIdentity(
            null, new UserProperties(self::$fullIdentityData['user_properties']), self::$fullIdentityData['device_id']
        );
        $httpClientStub = new HttpClientStub();
        $this->createClient($httpClientStub)->identifyUser($identity);

        $requestParams = $httpClientStub->getLastRequestBodyParams();

        $this->assertEquals(self::$apiKey, $requestParams['api_key']);
        $expectedRequestData = array_intersect_key(
            self::$fullIdentityData,
            array_flip(['device_id', 'user_properties'])
        );

        $this->assertEquals($expectedRequestData, json_decode($requestParams['identification'], true));
    }

    protected function createClient(ClientInterface $httpClient = null): Client
    {
        $client = new Client(self::$apiKey);
        if (null !== $httpClient) {
            $client->setHttpClient($httpClient);
        }
        return $client;
    }

    protected function createAllParamsIdentity(string $userId = null): UserIdentity
    {
        return (
            new UserIdentity(
                $userId ?? self::$fullIdentityData['user_id'],
                new UserProperties(self::$fullIdentityData['user_properties'])
            )
        )
            ->setDeviceId(self::$fullIdentityData['device_id'])
            ->setCountry(self::$fullIdentityData['country'])
            ->setCity(self::$fullIdentityData['city'])
            ->setAppVersion(self::$fullIdentityData['app_version'])
            ->setLanguage(self::$fullIdentityData['language'])
            ->setPlatformData(
                new PlatformData(
                    self::$fullIdentityData['platform'],
                    self::$fullIdentityData['os_name'],
                    self::$fullIdentityData['os_version'],
                    self::$fullIdentityData['device_brand'],
                    self::$fullIdentityData['device_manufacturer'],
                    self::$fullIdentityData['device_model'],
                    self::$fullIdentityData['carrier']
                )
            )
            ->setRegion(self::$fullIdentityData['region'])
            ->setDma(self::$fullIdentityData['dma'])
            ->setStartVersion(self::$fullIdentityData['start_version'])
            ->setPaying(self::$fullIdentityData['paying']);
    }
}