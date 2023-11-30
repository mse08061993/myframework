<?php

namespace Simplex;

use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\EventDispatcher\EventDispatcher;

class Framework implements HttpKernelInterface
{
    public function __construct(
        private EventDispatcher $dispatcher,
        private UrlMatcherInterface $urlMatcher,
        private ControllerResolverInterface $controllerResolver,
        private ArgumentResolverInterface $argumentResolver,
    ) {
    }

    public function handle(
        Request $request,
        int $type = HttpKernelInterface::MAIN_REQUEST,
        bool $catch = true
    ): Response {
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

        $this->dispatcher->dispatch(new ResponseEvent($request, $response), 'response');

        return $response;
    }
}
