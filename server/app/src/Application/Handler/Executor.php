<?php

namespace App\Application\Handler;

use App\Application\Event\EventsDispatcherStatic;
use App\Domain\Common\DomainTransformer;
use App\Domain\Common\StoredEvent;
use App\Infrastructure\Persistence\Doctrine\DoctrineStoredEventRepository;
use Doctrine\ORM\EntityManagerInterface;

final class Executor
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var DoctrineStoredEventRepository
     */
    private $storedEventRepository;

    public function __construct(
        EntityManagerInterface $em,
        DoctrineStoredEventRepository $storedEventRepository
    ) {
        $this->em = $em;
        $this->storedEventRepository = $storedEventRepository;
    }

    public function executeHandler(
        object $handler,
        array $params,
        DomainTransformer $transformer = null
    ) {
        if (!is_callable($handler)) {
            throw new \LogicException();
        }

        return $this->doAction(
            ($handler)(...$params),
            $transformer
        );
    }

    public function executeCallback(\Closure $closure, DomainTransformer $transformer = null)
    {
        return $this->doAction(
            $closure(),
            $transformer
        );
    }

    private function doAction($result, ?DomainTransformer $transformer)
    {
        if ($transformer !== null && $result !== null) {
            $result = $transformer->transform($result);
        }

        $storedEvents = [];
        foreach (EventsDispatcherStatic::getInstance()->getDispatchedEvents() as $event) {
            $this->storedEventRepository->add(
                $storedEvent = new StoredEvent($event)
            );
            $storedEvents[] = $storedEvent;
        }

        $this->em->flush();

        return $result;
    }
}
