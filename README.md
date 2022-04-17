# Avito REST client library

A simple Avito REST client library and example for PHP.

API Documentation [https://developers.avito.ru/api-catalog](https://developers.avito.ru/api-catalog)

### Installing

Via Composer:

```bash
composer require progdog-ru/avito-rest-api-php
```

### Usage

```php
<?php
require 'vendor/autoload.php';

use Avito\RestApi\ApiClient;
use Avito\RestApi\Storage\FileStorage;

// API credentials from https://www.avito.ru/professionals/api
define('API_CLIENT_ID', '');
define('API_CLIENT_SECRET', '');
define('PATH_TO_ATTACH_FILE', __FILE__);

$AvitoApiClient = new ApiClient(API_CLIENT_ID, API_CLIENT_SECRET, new FileStorage());

```