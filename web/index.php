<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;

define('PAGES_DIR', __DIR__ . '/../src/pages/');

$request = Request::createFromGlobals();
$routes = require_once __DIR__ . '/../src/app.php';

$context = new RequestContext();
$context->fromRequest($request);
$matcher = new UrlMatcher($routes, $context);

$controllerResolver = new ControllerResolver();
$argumentResolver = new ArgumentResolver();

try {
    $attributes = $matcher->match($request->getPathInfo());
    $request->attributes->add($attributes);
    $controller = $controllerResolver->getController($request);
    $arguments = $argumentResolver->getArguments($request, $controller);
    $response = call_user_func_array($controller, $arguments);
} catch (ResourceNotFoundException $ex) {
    $response = new Response('Page not found', 404);
} catch (Exception $ex) {
    $response = new Response('An error occured', 500);
}

$response->send();

function render_template(Request $request): Response
{
    extract($request->attributes->all(), EXTR_SKIP);
    ob_clean();
    require_once PAGES_DIR . $_route . '.php';
    $content = ob_get_clean();
    return new Response($content);
}
