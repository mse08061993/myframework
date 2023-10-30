<?php

require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$request = Request::createFromGlobals();
$response = new Response();

$name = $request->query->get('name', 'World');
$response->setContent(sprintf("Hello, %s", htmlspecialchars($name, encoding: 'utf-8')));

$response->send();
