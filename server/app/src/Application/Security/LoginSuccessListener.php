<?php

namespace App\Application\Security;

use App\Domain\User\Transformer\UserFullTransformer;
use App\Domain\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class LoginSuccessListener
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();

        if (!$user instanceof User) {
            return;
        }

        $data['user'] = (new UserFullTransformer())->transform($user);

        $event->setData($data);

        $this->clearAllPreviousRefreshTokens(
            $data['refresh_token'] ?? '',
            $user->getNickname()
        );
    }

    public function onJWTCreated(JWTCreatedEvent $event)
    {
        /** @var User $user */
        $user = $event->getUser();
        $payload = $event->getData();
        $payload['sessionId'] = $user->regenerateSessionId();
        $event->setData($payload);

        $this->em->flush($user);
    }

    private function clearAllPreviousRefreshTokens(string $currentRefreshToken, string $currentUser)
    {
        /** @var RefreshToken $token */
        foreach ($this->em->getRepository(RefreshToken::class)->findBy(['username' => $currentUser]) as $token) {
            if ($token->getRefreshToken() !== $currentRefreshToken) {
                $this->em->remove($token);
            }
        }

        $this->em->flush();
    }
}
