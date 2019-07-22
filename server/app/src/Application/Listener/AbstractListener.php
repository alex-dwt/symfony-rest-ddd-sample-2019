<?php

namespace App\Application\Listener;

use App\Application\Event\EventsDispatcher;
use App\Domain\User\Event\UserCreatedEvent;
use App\Domain\User\Event\UserPasswordWasResetEvent;
use App\Domain\User\Event\UserRestorePasswordLinkCreatedEvent;
use App\Domain\User\User;
use App\Infrastructure\Persistence\Doctrine\DoctrineUserRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

abstract class AbstractListener implements EventSubscriberInterface
{
    protected const EVENTS_BEFORE_FLUSH = [];

    protected const EVENTS_AFTER_FLUSH = [];

    public static function getSubscribedEvents()
    {
        $res = [];

        foreach ([
            [EventsDispatcher::BEFORE_FLUSH, static::EVENTS_BEFORE_FLUSH, 'BeforeFlush'],
            [EventsDispatcher::AFTER_FLUSH, static::EVENTS_AFTER_FLUSH, 'AfterFlush'],
                     ] as [$type, $events, $postfix]) {
            foreach ($events as $eventClassName) {
                $short = substr((string) strrchr($eventClassName, '\\'), 1);
                $res[$eventClassName . $type] = "on$short$postfix";
            }
        }

        return $res;
    }
}
