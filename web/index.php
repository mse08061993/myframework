<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

define('PAGES_DIR', __DIR__ . '/../src/pages/');

$request = Request::createFromGlobals();

$routes = require_once __DIR__ . '/../src/routes.php';
$context = new RequestContext();
$context->fromRequest($request);
$matcher = new UrlMatcher($routes, $context);

try {
    $path = $request->getPathInfo();
    $attributes = $matcher->match($path);
    ob_start();
    extract($attributes, EXTR_SKIP);
    include PAGES_DIR . $_route . '.php';
    $response = new Response(ob_get_clean());
} catch (ResourceNotFoundException $ex) {
    $response = new Response('Page not found', 404);
} catch (Exception $ex) {
    $response = new Response('An error occured', 500);
}

$response->send();
