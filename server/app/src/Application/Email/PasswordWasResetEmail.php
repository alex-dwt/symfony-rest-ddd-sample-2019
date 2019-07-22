<?php

namespace App\Application\Email;

use App\Domain\User\User;

class PasswordWasResetEmail extends AbstractEmail
{
    public function __construct(User $user, string $password)
    {
        $this->templateParams = compact('user', 'password');
        $this->to = $user->getEmail();
        $this->lang = $user->getSettings()->getLanguage();
    }

    public function subject(): string
    {
        return 'Password was reset successfully';
    }

    public function templatePath(): string
    {
        return 'profile/password_was_reset.html.twig';
    }
}