<?php

namespace App\Domain\User;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Embeddable
 */
class UserPasswordRestoreLink
{
    const TTL = 1; // hours

    /**
     * @var string|null
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
    private $hash;

    /**
     * @var \DateTimeImmutable|null
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $createdAt;

    public function __construct(bool $createHash = false)
    {
        if ($createHash) {
            $this->hash = Uuid::uuid4()->toString();
            $this->createdAt = new \DateTimeImmutable();
        }
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function isValid(): bool
    {
        return $this->hash
            && $this->createdAt
            && new \DateTime() <= $this
                ->createdAt
                ->add(new \DateInterval('PT' . self::TTL . 'H'));
    }
}
