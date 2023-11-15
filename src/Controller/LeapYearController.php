<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LeapYearController
{
    public function index(Request $request): Response
    {
        $year = $request->attributes->get('year');
        if ($this->isLeapYear($year)) {
            return new Response('Yes, the year is leap');
        }
        return new Response('No, the year is not leap');
    }

    private function isLeapYear(?int $year): bool
    {
        $year ??= (int)date('Y');

        return (0 === $year % 400 || (0 === $year % 4 && 0 !== $year % 100));
    }
}
