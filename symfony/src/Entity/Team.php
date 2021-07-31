<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TeamRepository::class)
 */
class Team
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public int $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private string $identifier;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isYouthTeam = true;

    /**
     * @ORM\ManyToMany(targetEntity=Trainer::class, inversedBy="teams", cascade={"persist"})
     */
    private Collection $trainer;

    /**
     * @ORM\OneToMany(targetEntity=Player::class, mappedBy="team")
     */
    private Collection $players;

    public function __construct()
    {
        $this->players = new ArrayCollection();
        $this->trainer = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): self
    {
        $this->identifier = str_replace(' ', '-', strtolower($identifier));

        return $this;
    }

    public function getIsYouthTeam(): ?bool
    {
        return $this->isYouthTeam;
    }

    public function setIsYouthTeam(bool $isYouthTeam): self
    {
        $this->isYouthTeam = $isYouthTeam;

        return $this;
    }

    /**
     * @return Collection|Trainer[]
     */
    public function getTrainer(): Collection
    {
        return $this->trainer;
    }

    public function addTrainer(Trainer $trainer): self
    {
        if (!$this->trainer->contains($trainer)) {
            $this->trainer->add($trainer);
            $trainer->addTeam($this);
        }

        return $this;
    }

    public function removeTrainer(Trainer $trainer): self
    {
        $this->trainer->removeElement($trainer);

        return $this;
    }

    /**
     * @return Collection|Player[]
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Player $player): self
    {
        if (!$this->players->contains($player)) {
            $this->players[] = $player;
            $player->setTeam($this);
        }

        return $this;
    }

    public function removePlayer(Player $player): self
    {
        if ($this->players->removeElement($player)) {
            // set the owning side to null (unless already changed)
            if ($player->getTeam() === $this) {
                $player->setTeam(null);
            }
        }

        return $this;
    }
}
