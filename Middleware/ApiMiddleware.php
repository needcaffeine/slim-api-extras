<?php

namespace Needcaffeine\Slim\Extras\Middleware;

use Slim\Slim;
use Slim\Middleware;

class ApiMiddleware extends Middleware
{
    public function __construct()
    {
        $app = Slim::getInstance();

        // Debug needs to be turned off for custom error
        // handlers to be invoked. Else you'll see Slim's
        // "pretty errors".
        $app->config('debug', false);

        // Exception handler.
        $app->error(function (\Exception $e) use ($app) {
            $app->render(500, array(
                'notifications' => array($e->getMessage())
            ));
        });

        // http://docs.slimframework.com/#Not-Found-Handler
        $app->notFound(function () use ($app) {
            $app->render(400, array(
                'notifications' => array('Not found')
            ));
        });
    }

    public function call()
    {
        // Call next middleware.
        $this->next->call();
    }
}
