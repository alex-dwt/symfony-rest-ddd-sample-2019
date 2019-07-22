<?php

namespace App\Domain\User;

use App\Domain\Common\ToArrayTransformable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class UserSettings implements ToArrayTransformable
{
    // only 2 symbols
    const LANGUAGES = [
        'en',
        'ru'
    ];

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $language;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $timezone;

    public function __construct(
        string $language,
        string $timezone
    ){
        $this->language = $language;
        $this->timezone = $timezone;
    }

    public function update(array $params): self
    {
        return new self(
            $params['language'] ?? $this->language,
            $params['timezone'] ?? $this->timezone
        );
    }

    public function toArray(): array
    {
        return [
            'language' => $this->language,
            'timezone' => $this->timezone,
        ];
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function getTimezone(): string
    {
        return $this->timezone;
    }
}
