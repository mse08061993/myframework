<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Simplex\Framework;
use Simplex\GoogleListener;
use Simplex\ContentLengthListener;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\HttpCache\Esi;
use Symfony\Component\HttpKernel\HttpCache\HttpCache;
use Symfony\Component\HttpKernel\HttpCache\Store;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

$request = Request::createFromGlobals();
$routes = require_once __DIR__ . '/../src/app.php';

$context = new RequestContext();
$urlMatcher = new UrlMatcher($routes, $context);

$controllerResolver = new ControllerResolver();
$argumentResolver = new ArgumentResolver();

$eventDispatcher = new EventDispatcher();
$eventDispatcher->addSubscriber(new ContentLengthListener());
$eventDispatcher->addSubscriber(new GoogleListener());

$framework = new Framework($eventDispatcher, $urlMatcher, $controllerResolver, $argumentResolver);
$framework = new HttpCache($framework, new Store(__DIR__.'/../cache', new Esi()));

$response = $framework->handle($request);
$response->send();
