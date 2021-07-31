<?php

namespace App\Entity;

use App\Entity\Traits\AddressTrait;
use App\Entity\Traits\PhoneTrait;
use App\Entity\Traits\PlainPasswordTrait;
use App\Entity\Traits\TimestampTrait;
use App\Repository\TrainerRepository;
use Cooolinho\Bundle\SecurityBundle\Entity\Traits\CredentialsTrait;
use Cooolinho\Bundle\SecurityBundle\Entity\Traits\EmailTrait;
use Cooolinho\Bundle\SecurityBundle\Entity\Traits\NameTrait;
use Cooolinho\Bundle\SecurityBundle\Entity\Traits\RoleTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=TrainerRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Trainer implements UserInterface
{
    use NameTrait, RoleTrait, EmailTrait, CredentialsTrait, AddressTrait, PlainPasswordTrait, PhoneTrait, TimestampTrait;

    public const ROLE_TRAINER = 'ROLE_TRAINER';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\ManyToMany(targetEntity=Team::class, mappedBy="trainer", cascade={"persist"})
     */
    private Collection $teams;

    public function __construct()
    {
        $this->plainPassword = '';
        $this->addRole(self::ROLE_TRAINER);
        $this->teams = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getFullname();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Team[]
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(Team $team): self
    {
        if (!$this->teams->contains($team)) {
            $this->teams->add($team);
            $team->addTrainer($this);
        }

        return $this;
    }

    public function removeTeam(Team $team): self
    {
        if ($this->teams->removeElement($team)) {
            $team->removeTrainer($this);
        }

        return $this;
    }
}
