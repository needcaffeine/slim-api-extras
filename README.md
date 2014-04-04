# Slim API Extras

This library is an extension for the [Slim Framework](https://github.com/codeguy/Slim), allowing for easy implementation of APIs with RESTful responses.

## Getting Started

### Installation

You may install this library with [Composer](https://getcomposer.org) and [Packagist](https://packagist.org/) (recommended) or manually. In order to install this library using Composer, modify your composer.json file to add a reference to this library:

```json
    {
        "require": {
            "slim/slim": "2.4.2",
            "needcaffeine/slim-api-extras": "dev-master"
        }
    }
```

### Usage (via Composer)

```php
    require 'vendor/autoload.php';

    use \Needcaffeine\Slim\Extras\ApiView;
    use \Needcaffeine\Slim\Extras\ApiMiddleware;

    $app = new \Slim\Slim();
    $app->view(new ApiView());
    $app->add(new ApiMiddleware());

    // Example method demonstrating notifications
    // and non-200 HTTP response.
    $app->get('/hello', function () use ($app) {
        $request = $app->request();
        $name = $request->get('name');

        if ($name) {
            $response = "Hello, {$name}!";
        } else {
            $response['notifications'][] = 'Name not provided.';
            $responseCode = 400;
        }

        $responseCode = $responseCode ?: 200;
        $app->render($responseCode, $response);
    });
```

#### Example


