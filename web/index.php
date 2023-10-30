<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

define('PAGES_DIR', __DIR__ . '/../src/pages/');

$request = Request::createFromGlobals();
$response = new Response();

$path = $request->getPathInfo();

$map = [
    '/hello' => PAGES_DIR . 'hello.php',
    '/goodbay' => PAGES_DIR . 'goodbay.php',
];

if (isset($map[$path])) {
    ob_start();
    include $map[$path];
    $response->setContent(ob_get_clean());
} else {
    $response->setContent('Page not found');
    $response->setStatusCode(404);
}

$response->send();
