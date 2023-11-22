<?php

namespace Simplex\Test;

use Calendar\Controller\LeapYearController;
use Simplex\Framework;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpFoundation\Request;

class FrameworkTest extends TestCase
{
    public function testNotFoundHandling(): void
    {
        $framework = $this->getFrameworkForException(new ResourceNotFoundException());

        $response = $framework->handle(new Request());

        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testErrorHandling(): void
    {
        $framework = $this->getFrameworkForException(new \RuntimeException());

        $response = $framework->handle(new Request());

        $this->assertEquals(500, $response->getStatusCode());
    }

    public function testControllerResponse(): void
    {
        $urlMatcher = $this->createMock(UrlMatcherInterface::class);

        $urlMatcher
            ->expects($this->once())
            ->method('getContext')
            ->will($this->returnValue($this->createMock(RequestContext::class)))
        ;

        $urlMatcher
            ->expects($this->once())
            ->method('match')
            ->will($this->returnValue([
                '_route' => 'is_leap_year',
                'year' => 2004,
                '_controller' => LeapYearController::class . '::index',
            ]))
        ;

        $controllerResolver = new ControllerResolver();
        $argumentResolver = new ArgumentResolver();

        $framework = new Framework($urlMatcher, $controllerResolver, $argumentResolver);
        $response = $framework->handle(new Request());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Yes, the year is leap', $response->getContent());
    }

    private function getFrameworkForException(\Exception $exception): Framework
    {
        $urlMatcher = $this->createMock(UrlMatcherInterface::class);

        $urlMatcher
            ->expects($this->once())
            ->method('getContext')
            ->will($this->returnValue($this->createMock(RequestContext::class)))
        ;

        $urlMatcher
            ->expects($this->once())
            ->method('match')
            ->will($this->throwException($exception))
        ;

        $controllerResolver = $this->createMock(ControllerResolverInterface::class);
        $argumentResolver = $this->createMock(ArgumentResolverInterface::class);

        return new Framework($urlMatcher, $controllerResolver, $argumentResolver);
    }
}
