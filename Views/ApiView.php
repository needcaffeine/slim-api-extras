<?php

namespace Needcaffeine\Slim\Extras\Views;

use Slim\Slim;
use Slim\View;

class ApiView extends View
{
    public function render($statusCode = 200, $data = null)
    {
        $app = Slim::getInstance();

        $response = $this->all();

        // Set the appropriate headers.
        $app->response()->status($statusCode);
        $app->response()->header('Content-Type', 'application/json; charset=utf-8');

        // Remove Slim's flash messages from the data array.
        unset($response['flash']);

        // Some applications care about a success/failure value. This is common in APIs
        // where the request itself succeeded but an underlying operation failed.
        if (!isset($response['meta']['result']) &&
            ((int) $statusCode >= 200 && (int) $statusCode < 400)
        ) {
            $response['meta']['result'] = 'success';
        } else {
            $response['meta']['result'] = 'failure';
        }

        // This is useful for debugging even though the status code is in the headers.
        // Please be responsible and use this only for debugging, as support may be
        // removed in future versions.
        $response['meta']['status'] = $statusCode;

        // Depending on the PHP version, we'll either output this in
        // pretty print or not.
        if (PHP_VERSION_ID >= 50400 && APPLICATION_ENV == 'development') {
            $body = json_encode($response, JSON_PRETTY_PRINT);
        } else {
            $body = json_encode($response);
        }

        // Enable support for JSONP
        if ($app->request()->get('callback')) {
            $body = sprintf('%s(%s)', $app->request()->get('callback'), $body);
        }

        $app->response()->body($body);
    }
}
