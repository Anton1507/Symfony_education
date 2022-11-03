<?php

namespace App\Entity;

use App\Repository\SubscriberRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: SubscriberRepository::class)]
class Subscriber
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $email;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeInterface $createdAt;

    #[ORM\PrePersist]
    public function setCreateAtValue(): void
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): Subscriber
    {
        $this->email = $email;

        return $this;
    }

    public function getCreateAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreateAt(DateTimeInterface $createdAt): Subscriber
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}