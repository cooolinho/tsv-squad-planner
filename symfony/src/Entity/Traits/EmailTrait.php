<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait EmailTrait
{
    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    protected string $email;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->email;
    }
}
