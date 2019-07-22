<?php

namespace App\Domain\Common;

use Doctrine\ORM\Mapping as ORM;
use App\Application\Event\Event;

/**
 * @ORM\Entity
 * @ORM\Table(
 *    name="stored_events"
 * )
 */
class StoredEvent
{
    use InitEntityTrait;

    const STATUS_NEW = 'new';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';

    /**
     * @ORM\Column(type="text")
     */
    private $eventName;

    /**
     * @var Event
     * @ORM\Column(type="object")
     */
    private $event;

    /**
     * @ORM\Column(type="text")
     */
    private $failedErrorText = '';

    /**
     * @ORM\Column(type="string")
     */
    private $status = self::STATUS_NEW;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $processingStartedAt;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $processingFinishedAt;

    public function __construct(Event $event)
    {
        $this->init();

        $event = $event->saveToDb();

        $this->eventName = get_class($event);
        $this->event = $event;
    }

    public function getEvent(): Event
    {
        return $this->event;
    }

    public function markAsSuccess()
    {
        $this->status = self::STATUS_SUCCESS;

        $this->touchUpdatedAt();
    }

    public function markAsFailed(string $text)
    {
        $this->status = self::STATUS_FAILED;
        $this->failedErrorText = $text;

        $this->touchUpdatedAt();
    }

    public function isSuccess(): bool
    {
        return $this->status === self::STATUS_SUCCESS;
    }

    public function refreshEventObject()
    {
        $this->event = clone $this->event;
    }

    public function touchProcessingStartedAt()
    {
        $this->processingStartedAt = new \DateTimeImmutable();
    }

    public function touchProcessingFinishedAt()
    {
        $this->processingFinishedAt = new \DateTimeImmutable();
    }
}
