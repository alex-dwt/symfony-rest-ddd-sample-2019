<?php

namespace App\Application\Listener\System;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;

class KernelViewListener
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(GetResponseForControllerResultEvent $event)
    {
        $result = (array) $event->getControllerResult();

        if (!$code = $event
                ->getRequest()
                ->attributes
                ->get(KernelControllerListener::RESPONSE_CODE_PARAM_NAME)
        ) {
            $code = ($result === [] ? 204 : 200);
        }

        $event->setResponse(
            new JsonResponse(
                $result,
                $code
            )
        );
    }
}
