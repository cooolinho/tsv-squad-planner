<?php

namespace App\Entity;

use App\Repository\TrainerRepository;
use Cooolinho\Bundle\SecurityBundle\Entity\Traits\CredentialsTrait;
use Cooolinho\Bundle\SecurityBundle\Entity\Traits\EmailTrait;
use Cooolinho\Bundle\SecurityBundle\Entity\Traits\NameTrait;
use Cooolinho\Bundle\SecurityBundle\Entity\Traits\RoleTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=TrainerRepository::class)
 */
class Trainer implements UserInterface
{
    use NameTrait, RoleTrait, EmailTrait, CredentialsTrait;

    public const ROLE_TRAINER = 'ROLE_TRAINER';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\OneToOne(targetEntity=Team::class, inversedBy="trainer", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Team $team = null;
    protected ?string $plainPassword;

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

    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }
}
