<?php

namespace Calendar\Controller;

use Calendar\Model\LeapYear;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LeapYearController
{
    public function index(Request $request): Response
    {
        $leapYear = new LeapYear();
        $year = $request->attributes->get('year');
        if ($leapYear->isLeapYear($year)) {
            $response = new Response('Yes, the year is leap');
        } else {
            $response = new Response('No, the year is not leap.'.rand());
        }

        $response->setTtl(10);

        return $response;
    }
}
