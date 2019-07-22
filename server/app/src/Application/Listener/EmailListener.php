<?php

namespace App\Application\Listener;

use App\Application\Email\PasswordResetConfirmationEmail;
use App\Application\Email\PasswordWasResetEmail;
use App\Application\Service\EmailSender;
use App\Application\Event\EventsDispatcher;
use App\Domain\User\Event\UserCreatedEvent;
use App\Domain\User\Event\UserPasswordWasResetEvent;
use App\Domain\User\Event\UserRestorePasswordLinkCreatedEvent;
use App\Domain\User\User;
use App\Infrastructure\Persistence\Doctrine\DoctrineUserRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EmailListener extends AbstractListener
{
    protected const EVENTS_AFTER_FLUSH = [
        UserPasswordWasResetEvent::class,
        UserRestorePasswordLinkCreatedEvent::class,
    ];

    /**
     * @var DoctrineUserRepository
     */
    private $userRepository;

    /**
     * @var EmailSender
     */
    private $emailSender;

    public function __construct(
        DoctrineUserRepository $userRepository,
        EmailSender $emailSender
    ) {
        $this->userRepository = $userRepository;
        $this->emailSender = $emailSender;
    }

    public function onUserPasswordWasResetEventAfterFlush(UserPasswordWasResetEvent $event)
    {
        /** @var User $user */
        if (!$user = $this->userRepository->get($event->userId())) {
            return;
        }

        $this->emailSender->send(new PasswordWasResetEmail($user, $event->newPassword()));
    }

    public function onUserRestorePasswordLinkCreatedEventAfterFlush(UserRestorePasswordLinkCreatedEvent $event)
    {
        /** @var User $user */
        if (!$user = $this->userRepository->get($event->userId())) {
            return;
        }

        $this->emailSender->send(new PasswordResetConfirmationEmail($user));

    }
}
