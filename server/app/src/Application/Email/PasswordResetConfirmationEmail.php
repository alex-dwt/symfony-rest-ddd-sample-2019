<?php

namespace App\Application\Email;

use App\Domain\User\User;

class PasswordResetConfirmationEmail extends AbstractEmail
{
    public function __construct(User $user)
    {
        $this->templateParams = compact('user');
        $this->to = $user->getEmail();
        $this->lang = $user->getSettings()->getLanguage();
    }

    public function subject(): string
    {
        return 'Request for reset of password';
    }

    public function templatePath(): string
    {
        return 'profile/password_reset_confirmation.html.twig';
    }
}