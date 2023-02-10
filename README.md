# Disposable Email Checker
The library allows checking if the email address is disposable.

## How it works

The library utilizes a third-party [Debounce Disposable Email API](https://debounce.io/free-disposable-check-api/), by sending requests and receiving responses. 
While the API is free to use, there are restrictions on the number of requests allowed. 
To minimize the number of requests, the library maintains its list of well-known email domains and implements a caching mechanism for previous responses.

## Requirements

PHP ^8.1

guzzlehttp/guzzle ^7.0

## Installation

Run the Composer require command to install the package:

`composer require epifrin/disposable-email-checker`

## Usage

Basic use of DisposableEmailChecker

```php
<?php

require_once './vendor/autoload.php';

use Epifrin\DisposableEmailChecker\DisposableEmailChecker;

$disposableEmailChecker = new DisposableEmailChecker();
$disposableEmailChecker->isEmailDisposable('foo@gmail.com'); // false
$disposableEmailChecker->isEmailDisposable('foo@mailinator.com'); // true
```
Please note. In case of reaching the Debounce API limitation will be thrown `RateLimitException`.

To reduce the number of requests to the Debounce API, it is recommended to pass a PSR-16 cache implementation as the first parameter when sending a significant amount of requests.

```php
<?php

require_once './vendor/autoload.php';

use Epifrin\DisposableEmailChecker\DisposableEmailChecker;

$cache = new RedisCache(); // for example some Redis Cache implementation
$disposableEmailChecker = new DisposableEmailChecker($cache);
$disposableEmailChecker->isEmailDisposable('foo@mailinator.com'); // true

$disposableEmailChecker->isEmailDisposable('foo@mailinator.com'); // this request will not be sent to the Debounce API while it present in Redis
```

If you want to use your custom disposable email checker you can implement `CheckerInterface` and pass it as a second parameter.

```php
<?php

class MyOwnChecker implements \Epifrin\DisposableEmailChecker\Checker\CheckerInterface
{
    public function isEmailDisposable(string $emailAddress): bool
    {
        // TODO: Implement isEmailDisposable() method.
    }
}
```

```php
<?php

require_once './vendor/autoload.php';

use Epifrin\DisposableEmailChecker\DisposableEmailChecker;

$cache = new RedisCache();
$disposableEmailChecker = new DisposableEmailChecker(new Cache(), new MyOwnChecker());
```

## Trust domains

Trust domains help to reduce the number of requests to the Debounce API.
Emails from trust domains will not be checked by the Debounce API.

How to add trust domains.

```php
<?php

require_once './vendor/autoload.php';

use Epifrin\DisposableEmailChecker\DisposableEmailChecker;

$disposableEmailChecker = new DisposableEmailChecker();
$disposableEmailChecker->addTrustDomains(['domain.com', 'my.domain.com']);

$disposableEmailChecker->isEmailDisposable('foo@domain.com'); // false
```

## Warning
Do not recommend using this library in the long-running application without your PSR-16 cache implementation.