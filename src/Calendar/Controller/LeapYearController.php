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
            return new Response('Yes, the year is leap');
        }
        return new Response('No, the year is not leap');
    }
}
