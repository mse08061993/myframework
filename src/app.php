<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

function isLeapYear(?int $year): bool
{
    $year ??= (int)date('Y');

    return (0 === $year % 400 || (0 === $year % 4 && 0 !== $year % 100));
}

$routes = new RouteCollection();
$routes->add(
    'hello',
    new Route(
        '/is_leap_year/{year}',
        [
            'year' => null,
            '_controller' => function (Request $request): Response {
                $year = $request->attributes->getInt('year');
                if (isLeapYear($year)) {
                    return new Response('Yes, the year is leap');
                }
                return new Response('No, the year is not leap');
            },
        ]
    )
);

return $routes;

