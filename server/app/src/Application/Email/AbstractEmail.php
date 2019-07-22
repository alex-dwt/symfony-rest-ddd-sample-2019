<?php

namespace App\Application\Email;

abstract class AbstractEmail
{
    /**
     * @var string
     */
    protected $to = '';

    /**
     * @var string
     */
    protected $lang = '';

    /**
     * @var array
     */
    protected $templateParams = [];

    public function to(): string
    {
        if ($this->to === '') {
            throw new \LogicException('EmailTo can not be empty');
        }

        return $this->to;
    }

    public function lang(): string
    {
        if ($this->lang === '') {
            throw new \LogicException('Language can not be empty');
        }

        return $this->lang;
    }

    public function templateParams(): array
    {
        if (!$this->templateParams || !is_array($this->templateParams)) {
            throw new \LogicException('TemplateParams can not be empty');
        }

        return $this->templateParams;
    }

    abstract public function subject(): string;

    abstract public function templatePath(): string;
}