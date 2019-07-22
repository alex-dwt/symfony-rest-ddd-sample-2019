<?php

namespace App\Application\Security;

use App\Domain\User\User;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\PreAuthenticationJWTUserToken;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Guard\JWTTokenAuthenticator as BaseAuthenticator;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class JwtTokenAuthenticator extends BaseAuthenticator
{
    /**
     * @param PreAuthenticationJWTUserToken $credentials
     * @param User $user
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        $sessionId = $credentials->getPayload()['sessionId'] ?? null;

        if ($sessionId !== $user->getSessionId()) {
            throw new AuthenticationException('Another session was started'); // todo
        }

        return true;
    }
}
