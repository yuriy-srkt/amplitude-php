# Amplitude PHP SDK
![PHP Composer](https://github.com/yuriy-srkt/amplitude-php/workflows/PHP%20Composer/badge.svg?branch=master)

Multifunctional [Amplitude](https://amplitude.com/) REST API PHP SDK with PSR-7 and PSR-18 support (HTTP Request, Response and Client interfaces).

- A simple interface for tracking your [events](https://developers.amplitude.com/docs/http-api-deprecated) and [identities](https://developers.amplitude.com/docs/identify-api) into Amplitude service.
- Can send both single events (identities) and bunch of them. 
- Uses PSR-7 interfaces for requests and responses, PSR-18 for HTTP client. This allows you
  to utilize other PSR-18 compatible libraries SDK. Guzzle HTTP client used by default.
- Supports Amplitude features: platform data, revenue data, etc.  

## Installing Amplitude PHP SDK

The recommended way to install SDK is through
[Composer](https://getcomposer.org/).

```bash
composer require srkt/amplitude-php
```

## Examples
There are two types of library usage: simple "one method call" and full featured OOP styled one.
### Event tracking
#### Create client
```php
// Create new Amplitude client
$client = new \Srkt\Amplitude\Client('your-api-key-goes-here');
```

#### Simple
```php
// Simple event tracking:
$client->log(
    'user123',
    'testEventType',
    ['eventProperty' => 'value'],
    ['userProperty' => 'value'],
    'deviceId'
); // PSR-7 Response
```

#### Full event object
```php
// Full data event tracking
$event = new \Srkt\Amplitude\Model\Event('user1', 'registration');

// This is not full list of data setters, please check all Event class setters
$event
    ->setAppVersion('1.0.0')
    ->setCountry('USA') 
    ->setCity('New York')
    ->setRevenueData(new \Srkt\Amplitude\Model\RevenueData(10.00, 5.00, 2, 'product1', 'sell'))
    ->setLanguage('en')
    ->setDeviceId('device-id-1')
    ->setPlatformData(
        new \Srkt\Amplitude\Model\PlatformData('Moblie', 'Android', '11.0')
    )
    ->setEventProperties(['property' => 'value'])
    ->setUserProperties(new \Srkt\Amplitude\Model\UserProperties(['userProperty' => 'value']));

$client->logEvent($event); // PSR-7 Response
```

#### Event with no userId but with deviceId
```php
// Event without userId tracking
$event = new \Srkt\Amplitude\Model\Event(null, 'registration', [], null, null, 'device-id1');
$client->logEvent($event); // PSR-7 Response
```

#### Multiple events in one request
```php
$events = [
    new \Srkt\Amplitude\Model\Event('user1', 'registration'),
    new \Srkt\Amplitude\Model\Event('user1', 'addPhoto'),
    new \Srkt\Amplitude\Model\Event('user1', 'addInfo'),
];
$client->logEvents($events); // PSR-7 Response
```

### User Identities tracking
#### Simple
```php
$client = new \Srkt\Amplitude\Client('your-api-key-goes-here');
$identity = new \Srkt\Amplitude\Model\UserIdentity(
    'userId1',
    new \Srkt\Amplitude\Model\UserProperties(['userProperty' => 'value'])
);
$client->identifyUser($identity); // PSR-7 Response
```

#### Full identity object
```php
// Full data identity tracking
$identity = new \Srkt\Amplitude\Model\UserIdentity(
    'userId1',
    new \Srkt\Amplitude\Model\UserProperties(['userProperty' => 'value'])
);

// This is not full list of data setters, please check all Event class setters
$identity
    ->setDeviceId('device-id')
    ->setLanguage('en')
    ->setStartVersion('1.0.0')
    ->setPlatformData()
    ->setPaying('paying')
    ->setPlatformData(
        new \Srkt\Amplitude\Model\PlatformData('Moblie', 'Android', '11.0')
    );

$client->identifyUser($identity); // PSR-7 Response
```

#### Multiple identities in one request
```php
$identities = [
    new \Srkt\Amplitude\Model\UserIdentity('user1', new \Srkt\Amplitude\Model\UserProperties(['property' => 'value'])),
    new \Srkt\Amplitude\Model\UserIdentity('user2', new \Srkt\Amplitude\Model\UserProperties(['property' => 'value'])),
    new \Srkt\Amplitude\Model\UserIdentity('user3', new \Srkt\Amplitude\Model\UserProperties(['property' => 'value'])),
];
$client->identifyUsers($identities); // PSR-7 Response
```

### Advanced usage
You can change default Guzzle HTTP client to any other supports PSR-18 interface, or you can setup Guzzle client options.
```php
$client = new \Srkt\Amplitude\Client('your-api-key-goes-here');
// Client adapter used for Guzzle versions prior to 7.0.0 
$httpClient = new \Srkt\Amplitude\Http\Client\GuzzlePsr18ClientAdapter([
    'timeout'         => 0,
    'allow_redirects' => false,
    'proxy'           => '192.168.16.1:10'
]);
$client->setHttpClient($httpClient);
```
