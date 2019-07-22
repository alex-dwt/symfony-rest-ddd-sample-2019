<?php

namespace App\Application\Listener;

use App\Application\Event\Event;
use App\Application\Event\EventsDispatcher;
use App\Domain\AdvisedGame\Event\AdvisedGameCreatedEvent;
use App\Domain\Announcement\Event\AnnouncementCreatedEvent;
use App\Domain\Chat\Event\ChatMessageCreatedEvent;
use App\Domain\Tournament\Event\TournamentCreatedEvent;
use App\Domain\User\Event\UserCreatedEvent;
use App\Infrastructure\Persistence\Doctrine\AbstractDoctrineRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PersistenceListener implements EventSubscriberInterface
{
    private const EVENTS = [
        TournamentCreatedEvent::class,
        UserCreatedEvent::class,
        AnnouncementCreatedEvent::class,
        AdvisedGameCreatedEvent::class,
        ChatMessageCreatedEvent::class,
    ];

    /**
     * @var AbstractDoctrineRepository[]|iterable
     */
    private $repositories;

    /**
     * @param AbstractDoctrineRepository[]|iterable $repositories
     */
    public function __construct(
        iterable $repositories
    ) {
        $this->repositories = $repositories;
    }

    public static function getSubscribedEvents()
    {
        return array_fill_keys(
            array_map(
                function (string $eventClass) {
                    return $eventClass . EventsDispatcher::BEFORE_FLUSH;
                },
                self::EVENTS
            ),
            'onEntityCreated'
        );
    }

    public function onEntityCreated(Event $event)
    {
        if (!$entity = $event->getEntityToPersist()) {
            return;
        }

        $className = get_class($entity);

        foreach ($this->repositories as $repository) {
            if ($repository->repositoryClassName() === $className) {
                $repository->add($entity);
                break;
            }
        }
    }
}
