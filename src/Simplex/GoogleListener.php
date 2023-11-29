<?php

namespace Simplex;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class GoogleListener implements EventSubscriberInterface
{
    public function onResponse(ResponseEvent $event): void
    {
        $response = $event->getResponse();
        if (
            $response->isRedirection()
            || ($response->headers->get('Content-Type') && false === strpos($response->headers->get('Content-Type'), 'html'))
            || 'html' !== $event->getRequest()->getRequestFormat()    
        ) {
            return;
        }
        $response->setContent($response->getContent() . '. GA code.');
    }

    public static function getSubscribedEvents()
    {
        return [
            'response' => 'onResponse'
        ];
    }
}
