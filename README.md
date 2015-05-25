[![Latest Stable Version](https://poser.pugx.org/needcaffeine/slim-api-extras/v/stable.svg)](https://packagist.org/packages/needcaffeine/slim-api-extras) [![Total Downloads](https://poser.pugx.org/needcaffeine/slim-api-extras/downloads.svg)](https://packagist.org/packages/needcaffeine/slim-api-extras) [![License](https://poser.pugx.org/needcaffeine/slim-api-extras/license.svg)](https://packagist.org/packages/needcaffeine/slim-api-extras)

# Slim API Extras

This library is an extension for the [Slim Framework](https://github.com/codeguy/Slim), allowing for easy implementation of APIs with RESTful responses.

## Getting Started

### Installation

It's recommended that you install this package via  [Composer](https://getcomposer.org).

```bash
$ composer require needcaffeine/slim-api-extras
```

### Usage

```php
<?php

require 'vendor/autoload.php';

use \Needcaffeine\Slim\Extras\Views\ApiView;
use \Needcaffeine\Slim\Extras\Middleware\ApiMiddleware;

// This would probably be loaded from a config file perhaps.
$config = array(
    'slim' => array(
        'debug' => true
    )
);

// Get the debug value from the config.
$debug = $config['slim']['debug'];

$app = new \Slim\Slim($config['slim']);
$app->view(new ApiView($debug));
$app->add(new ApiMiddleware($debug));

// Example method demonstrating notifications
// and non-200 HTTP response.
$app->get('/hello', function () use ($app) {
    $request = $app->request();
    $name = $request->get('name');

    if ($name) {
        $response = "Hello, {$name}!";

        $data = array("Red" => "dog", "Brown" => "dog");
        $response['data'] = $data;
    } else {
        $response = array();
        $response['notifications'][] = 'Name not provided.';
        $responseCode = 400;
    }

    $responseCode = $responseCode ?: 200;
    $app->render($responseCode, $response);
});

// Run the Slim application.
$app->run();
```

#### Example of responses

```bash
» curl -i "http://localhost/hello"
HTTP/1.1 400 Bad Request
Content-Type: application/json; charset=utf-8

{
    "notifications": "Name not provided.",
    "meta": {
        "result": "failure",
        "status": 400
    }
}

» curl -i "http://localhost/hello?name=Vic"
HTTP/1.1 200 OK
Content-Type: application/json; charset=utf-8

{
    "notifications": "Hello, Vic!",
    "data": {
        "Red": "dog",
        "Brown": "dog"
    },
    "meta": {
        "result": "success",
        "status": 200
    }
}
```
