<?php

namespace Simplex;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\Event;

class ResponseEvent extends Event
{
    public function __construct(
        private Request $request,
        private Response $response,
    ) {
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }
}
