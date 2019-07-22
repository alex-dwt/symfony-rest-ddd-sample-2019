<?php

namespace App\Application\Event;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class EventsDispatcher
{
    const AFTER_FLUSH = ':after_flush';
    const BEFORE_FLUSH = ':before_flush';

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(
        EntityManagerInterface $em,
        EventDispatcherInterface $dispatcher
    ) {
        $this->em = $em;
        $this->dispatcher = $dispatcher;
    }

    public function dispatch(Event $event, bool $isBeforeFlush)
    {
        $name = get_class($event);
        $name .= $isBeforeFlush
            ? self::BEFORE_FLUSH
            : self::AFTER_FLUSH;

        $this->dispatcher->dispatch($name, $event);
    }
}
