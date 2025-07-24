<?php
declare(strict_types=1);

namespace App\Middleware;

use Webman\MiddlewareInterface;
use Webman\Http\Request;
use Webman\Http\Response;

class StaticFile implements MiddlewareInterface
{
    /**
     * @param Request  $request
     * @param callable $handler
     *
     * @return Response
     */
    public function process(Request $request, callable $handler): Response
    {
        // Access to files beginning with. Is prohibited
        if (str_contains($request->path(), '/.')) {
            return response('<h1>403 forbidden</h1>', 403);
        }
        /**
         * @var Response $response
         */
        $response = $handler($request);
        // Add cross domain HTTP header
        $response->withHeaders([
            'Access-Control-Allow-Origin'      => '*',
            'Access-Control-Allow-Credentials' => 'true',
        ]);
        return $response;
    }
}