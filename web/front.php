<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Calendar\Controller\ErrorController;
use Simplex\Framework;
use Simplex\StringResponseListener;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\EventListener\ErrorListener;
use Symfony\Component\HttpKernel\EventListener\ResponseListener;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

$request = Request::createFromGlobals();
$routes = require_once __DIR__ . '/../src/app.php';
$requestStack = new RequestStack();

$context = new RequestContext();
$urlMatcher = new UrlMatcher($routes, $context);

$controllerResolver = new ControllerResolver();
$argumentResolver = new ArgumentResolver();

$eventDispatcher = new EventDispatcher();
$eventDispatcher->addSubscriber(new RouterListener($urlMatcher, $requestStack));
$eventDispatcher->addSubscriber(new ErrorListener('Calendar\Controller\ErrorController::exception'));
$eventDispatcher->addSubscriber(new ResponseListener('utf-8'));
$eventDispatcher->addSubscriber(new StringResponseListener());

$framework = new Framework($eventDispatcher, $controllerResolver, $requestStack, $argumentResolver);

$response = $framework->handle($request);
$response->send();
