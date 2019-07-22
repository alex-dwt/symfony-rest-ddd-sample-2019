<?php

namespace App\Application\Listener\System;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class KernelControllerListener
{
    const RESPONSE_CODE_PARAM_NAME = '_app_response_code';

    public function __invoke(FilterControllerEvent $event)
    {
        $controller = $event->getController()[0] ?? null;
        $actionName = $event->getController()[1] ?? null;

        if (!$controller || !$actionName) {
            return;
        }

//        $event->getRequest()->attributes->set(self::RESPONSE_CODE_PARAM_NAME, 201);
    }
}
