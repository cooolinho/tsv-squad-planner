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
    private int $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private ?string $name;

    /**
     * @ORM\OneToOne(targetEntity=Trainer::class, mappedBy="team", cascade={"persist", "remove"})
     */
    private ?Trainer $trainer;

    /**
     * @ORM\OneToMany(targetEntity=Player::class, mappedBy="team")
     */
    private Collection $players;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $youthClass;

    public function __construct()
    {
        $this->players = new ArrayCollection();
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

    public function getTrainer(): ?Trainer
    {
        return $this->trainer;
    }

    public function setTrainer(Trainer $trainer): self
    {
        // set the owning side of the relation if necessary
        if ($trainer->getTeam() !== $this) {
            $trainer->setTeam($this);
        }

        $this->trainer = $trainer;

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

    public function getYouthClass(): ?string
    {
        return $this->youthClass;
    }

    public function setYouthClass(string $youthClass): self
    {
        $this->youthClass = $youthClass;

        return $this;
    }
}
