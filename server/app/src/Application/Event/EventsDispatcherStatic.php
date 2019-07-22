<?php

namespace App\Application\Event;

class EventsDispatcherStatic
{
    /**
     * @var self|null
     */
    private static $instance;

    /**
     * @var EventsDispatcher
     */
    private $dispatcher;

    /**
     * @var array|Event[]
     */
    private $dispatchedEvents = [];

    private function __construct()
    {
        $this->dispatcher = $GLOBALS['kernel']
            ->getContainer()
            ->get(EventsDispatcher::class);
    }

    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function dispatch(Event $event)
    {
        $this->dispatcher->dispatch($event, true);

        $this->dispatchedEvents[] = $event;
    }

    /**
     * @return array|Event[]
     */
    public function getDispatchedEvents(): array
    {
        $res = $this->dispatchedEvents;

        $this->dispatchedEvents = [];

        return $res;
    }
}
