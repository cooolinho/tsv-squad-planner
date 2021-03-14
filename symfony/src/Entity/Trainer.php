<?php

namespace App\Entity;

use App\Entity\Traits\CredentialsTrait;
use App\Entity\Traits\EmailTrait;
use App\Entity\Traits\NameTrait;
use App\Entity\Traits\RoleTrait;
use App\Repository\TrainerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TrainerRepository::class)
 */
class Trainer implements UserInterface
{
    use RoleTrait, EmailTrait, CredentialsTrait, NameTrait;

    public const ROLE_TRAINER = 'ROLE_TRAINER';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Team::class, inversedBy="trainer", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $team;

    public function __toString(): string
    {
        return $this->getUsername();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(Team $team): self
    {
        $this->team = $team;

        return $this;
    }
}
