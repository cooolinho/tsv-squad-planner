<?php

namespace App\Entity;

use App\Entity\Traits\BirthdayTrait;
use App\Entity\Traits\NameTrait;
use App\Repository\PlayerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlayerRepository::class)
 */
class Player
{
    use BirthdayTrait, NameTrait;

    public const FOOT_UNKNOWN = 'unknown';
    public const FOOT_LEFT = 'left';
    public const FOOT_RIGHT = 'right';
    public const FOOT_BOTH = 'both';

    public static array $availableFoots = [
        'play.foot.unknown' => Player::FOOT_UNKNOWN,
        'player.foot.left' => Player::FOOT_LEFT,
        'play.foot.right' => Player::FOOT_RIGHT,
        'play.foot.both' => Player::FOOT_BOTH,
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string")
     */
    private string $foot = self::FOOT_UNKNOWN;

    /**
     * @ORM\ManyToOne(targetEntity=Team::class, inversedBy="players", cascade={"persist"})
     */
    private ?Team $team;

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

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): self
    {
        $this->team = $team;

        return $this;
    }
}
