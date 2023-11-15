<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Simplex\Framework;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;

$request = Request::createFromGlobals();
$routes = require_once __DIR__ . '/../src/app.php';

$context = new RequestContext();
$urlMatcher = new UrlMatcher($routes, $context);

$controllerResolver = new ControllerResolver();
$argumentResolver = new ArgumentResolver();

$framework = new Framework($urlMatcher, $controllerResolver, $argumentResolver);
$response = $framework->handle($request);
$response->send();
