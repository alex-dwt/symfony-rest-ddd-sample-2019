<?php

namespace App\Application\Listener\System;

use App\Application\Exception\EntityNotFoundException;
use App\Application\Exception\ForbiddenException;
use App\Application\Handler\User\Exception\WrongCurrentUserPassword;
use App\Domain\AdvisedGame\Exception\AdvisedGameEmptyUsersException;
use App\Domain\Chat\Exception\ChatMessageRecipientRestrictionException;
use App\Domain\User\Exception\UserCanNotInviteFriendException;
use App\Domain\User\Exception\UserCanNotInviteHimselfException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class KernelExceptionListener
{
    private const EXCEPTIONS = [
        UniqueConstraintViolationException::class => [409, 'Conflict'],
        NotFoundHttpException::class => [404, 'Entity is not found'],
        EntityNotFoundException::class => [404],
        ForbiddenException::class => [403],

        UserCanNotInviteHimselfException::class => [450],
        UserCanNotInviteFriendException::class => [451],
        ChatMessageRecipientRestrictionException::class => [452],
        AdvisedGameEmptyUsersException::class => [422],
    ];

    /**
     * @var bool
     */
    private $isProdEnv;

    public function __construct(string $kernelEnv)
    {
        $this->isProdEnv = strtolower($kernelEnv) === 'prod';
    }

    public function __invoke(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        $info = self::EXCEPTIONS[get_class($exception)] ?? null;

        if (!$info) {
            return;
        }

        /** @var int|null $code */
        $code = $info[0] ?? null;

        if (!$code) {
            return;
        }

        if ($this->isProdEnv) {
            $message = $info[1] ?? $exception->getMessage();
        } else {
            $message = $exception->getMessage();
        }

        $event->setResponse(
            new JsonResponse(
                compact('code', 'message'),
                $code
            )
        );
    }
}
