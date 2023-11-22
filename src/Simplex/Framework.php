<?php

namespace Simplex;

use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Framework
{
    public function __construct(
        private UrlMatcherInterface $urlMatcher,
        private ControllerResolverInterface $controllerResolver,
        private ArgumentResolverInterface $argumentResolver,
    ) {
    }

    public function handle(Request $request): Response
    {
        $this->urlMatcher->getContext()->fromRequest($request);

        try {
            $attributes = $this->urlMatcher->match($request->getPathInfo());
            $request->attributes->add($attributes);
            $controller = $this->controllerResolver->getController($request);
            $arguments = $this->argumentResolver->getArguments($request, $controller);
            $response = call_user_func_array($controller, $arguments);
        } catch (ResourceNotFoundException $ex) {
            $response = new Response('Page not found', 404);
        } catch (\Exception $ex) {
            $response = new Response('An error occured', 500);
        }

        return $response;
    }
}
