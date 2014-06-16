# Slim API Extras

This library is an extension for the [Slim Framework](https://github.com/codeguy/Slim), allowing for easy implementation of APIs with RESTful responses.

## Getting Started

### Installation

You may install this library with [Composer](https://getcomposer.org) and [Packagist](https://packagist.org/) (recommended) or manually. In order to install this library using Composer, modify your composer.json file to add a reference to this library:

```json
    {
        "require": {
            "slim/slim": ">=2.4.2",
            "needcaffeine/slim-api-extras": "dev-master"
        }
    }
```

### Usage (via Composer)

```php
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

```
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
```
```
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
