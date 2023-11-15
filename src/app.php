<?php

use App\Controller\LeapYearController;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$routes = new RouteCollection();
$routes->add(
    'is_leap_year',
    new Route(
        '/is_leap_year/{year}',
        [
            'year' => null,
            '_controller' => LeapYearController::class . '::index',
        ]
    )
);

return $routes;

