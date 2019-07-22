<?php

namespace App\Application\Event;

abstract class Event extends \Symfony\Component\EventDispatcher\Event
{
    public function saveToDb(): Event
    {
        return $this;
    }

    public function getEntityToPersist(): ?object
    {
        return null;
    }

    public function eventWasProcessed(bool $isSuccess)
    {
    }
}
