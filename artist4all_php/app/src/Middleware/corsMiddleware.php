<?php

namespace Artist4all\Middleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Tuupola\Middleware\CorsMiddleware as CorsHandler;

class corsMiddleware {
  public static function corsHandler($app)
    {
        $cors = new CorsHandler([
            "origin" => ["*"],
            "methods" => ["GET", "POST", "PUT", "PATCH", "DELETE", "OPTIONS"],
            "headers.allow" => ["Authorization", "X-API-KEY", "Origin", "X-Requested-With", "Content-Type", "Accept", "Access-Control-Request-Method"],
            "headers.expose" => [],
            "credentials" => true,
            "cache" => 0,
        ]);
        $app->add($cors);
    }
}