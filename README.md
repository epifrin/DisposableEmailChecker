# Disposable Email Checker
The library allows checking if the email address is disposable.

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

## Warning
Do not recommend using this library in the long-running application without your PSR-16 cache implementation.