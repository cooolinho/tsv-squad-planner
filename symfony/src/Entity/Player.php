<?php

namespace App\Entity;

use App\Entity\Traits\BirthdayTrait;
use App\Entity\Traits\NameTrait;
use App\Repository\PlayerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlayerRepository::class)
 */
class Player
{
    use BirthdayTrait, NameTrait;

    public const FOOT_LEFT = 'left';
    public const FOOT_RIGHT = 'right';
    public const FOOT_BOTH = 'both';

    public static $availableFoots = [
        'player.foot.left' => Player::FOOT_LEFT,
        'play.foot.right' => Player::FOOT_RIGHT,
        'play.foot.both' => Player::FOOT_BOTH,
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $foot;

    /**
     * @ORM\ManyToMany(targetEntity=Team::class, mappedBy="players")
     */
    private $teams;

    public function __construct()
    {
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

    public function setBirthday(\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getFoot(): ?string
    {
        return $this->foot;
    }

    public function setFoot(string $foot): self
    {
        $this->foot = $foot;

        return $this;
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
            $this->teams[] = $team;
            $team->addPlayer($this);
        }

        return $this;
    }

    public function removeTeam(Team $team): self
    {
        if ($this->teams->removeElement($team)) {
            $team->removePlayer($this);
        }

        return $this;
    }
}
