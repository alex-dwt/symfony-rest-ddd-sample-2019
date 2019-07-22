<?php

namespace App\Application\Service;

use App\Application\Email\AbstractEmail;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Translation\TranslatorInterface;

class EmailSender
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var EngineInterface
     */
    private $twig;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(
        \Swift_Mailer $mailer,
        EngineInterface $twig,
        TranslatorInterface $translator
    ) {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->translator = $translator;
    }

    public function send(AbstractEmail $email)
    {
        $this->translator->setLocale($email->lang());

        $subject = $this->translator->trans($email->subject());

        $message = (new \Swift_Message($subject))
            ->setFrom('send@example.com')
            ->setTo($email->to())
            ->setBody(
                $this->twig->render('emails/' . $email->templatePath(), $email->templateParams()),
                'text/html'
            );

        $this->mailer->send($message);
    }
}
