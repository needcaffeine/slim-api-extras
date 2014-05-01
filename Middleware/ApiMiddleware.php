<?php

namespace Needcaffeine\Slim\Extras\Middleware;

use Slim\Slim;
use Slim\Middleware;

class ApiMiddleware extends Middleware
{
    
    /**
     * @param boolean $verbose Indicates whether exceptions should be displayed with their stack trace for debugging.
     * 
     */
    public function __construct($verbose = false)
    {
        $app = Slim::getInstance();

        // Debug needs to be turned off for custom error
        // handlers to be invoked. Else you'll see Slim's
        // "pretty errors".
        $app->config('debug', false);

        // Exception handler.
        $app->error(function (\Exception $e) use ($app, $verbose) {
            $data = array(
                'notifications' => array($e->getMessage())
            );
            if ($verbose) {
                $data['trace'] = $e->getTrace();
            }
            $app->render(500, $data);
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
