<?php

namespace App\Entity;

use App\Entity\Traits\AddressTrait;
use App\Entity\Traits\PhoneTrait;
use App\Entity\Traits\TimestampTrait;
use App\Repository\PlayerRepository;
use Cooolinho\Bundle\SecurityBundle\Entity\Traits\BirthdayTrait;
use Cooolinho\Bundle\SecurityBundle\Entity\Traits\NameTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlayerRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Player
{
    use BirthdayTrait, NameTrait, AddressTrait, PhoneTrait, TimestampTrait;

    public const FOOT_UNKNOWN = 0;
    public const FOOT_LEFT = 1;
    public const FOOT_RIGHT = 2;
    public const FOOT_BOTH = 3;

    public const CLOTHING_FIT_SIZE_UNKNOWN = 0;
    public const CLOTHING_FIT_SIZE_TO_SMALL = 1;
    public const CLOTHING_FIT_SIZE_FITS = 2;

    public static array $footChoices = [
        'player.foot.unknown' => self::FOOT_UNKNOWN,
        'player.foot.left' => self::FOOT_LEFT,
        'player.foot.right' => self::FOOT_RIGHT,
        'player.foot.both' => self::FOOT_BOTH,
    ];

    public static array $clothingFitSizeChoices = [
        'player.clothing.fit_size.unknown' => self::CLOTHING_FIT_SIZE_UNKNOWN,
        'player.clothing.fit_size.to_small' => self::CLOTHING_FIT_SIZE_TO_SMALL,
        'player.clothing.fit_size.fits' => self::CLOTHING_FIT_SIZE_FITS,
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private int $foot = self::FOOT_UNKNOWN;

    /**
     * @ORM\Column(type="integer")
     */
    private int $trainingsJacket = self::CLOTHING_FIT_SIZE_UNKNOWN;

    /**
     * @ORM\Column(type="integer")
     */
    private int $trainingsTrousers = self::CLOTHING_FIT_SIZE_UNKNOWN;

    /**
     * @ORM\Column(type="integer")
     */
    private int $warmUpShirt = self::CLOTHING_FIT_SIZE_UNKNOWN;

    /**
     * @ORM\Column(type="integer")
     */
    private int $warmUpSweater = self::CLOTHING_FIT_SIZE_UNKNOWN;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private ?string $clothingDesiredSize = null;

    /**
     * @ORM\ManyToOne(targetEntity=Club::class, inversedBy="players")
     */
    private ?Club $club;

    /**
     * @ORM\ManyToOne(targetEntity=Team::class, inversedBy="players")
     */
    private ?Team $team;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
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

    public function getFoot(): int
    {
        return $this->foot;
    }

    public function getFootLabel(): string
    {
        return array_search($this->foot, self::$footChoices, true);
    }

    public function setFoot(string $foot): self
    {
        $this->foot = $foot;

        return $this;
    }

    public function getTrainingsJacket(): int
    {
        return $this->trainingsJacket;
    }

    public function getTrainingsJacketLabel(): string
    {
        return array_search($this->trainingsJacket, self::$clothingFitSizeChoices, true);
    }

    public function setTrainingsJacket(int $trainingsJacket): self
    {
        $this->trainingsJacket = $trainingsJacket;

        return $this;
    }

    public function getTrainingsTrousers(): int
    {
        return $this->trainingsTrousers;
    }

    public function getTrainingsTrousersLabel(): string
    {
        return array_search($this->trainingsTrousers, self::$clothingFitSizeChoices, true);
    }

    public function setTrainingsTrousers(int $trainingsTrousers): self
    {
        $this->trainingsTrousers = $trainingsTrousers;

        return $this;
    }

    public function getWarmUpShirt(): int
    {
        return $this->warmUpShirt;
    }

    public function getWarmUpShirtLabel(): string
    {
        return array_search($this->warmUpShirt, self::$clothingFitSizeChoices, true);
    }

    public function setWarmUpShirt(int $warmUpShirt): self
    {
        $this->warmUpShirt = $warmUpShirt;

        return $this;
    }

    public function getWarmUpSweater(): int
    {
        return $this->warmUpSweater;
    }

    public function getWarmUpSweaterLabel(): string
    {
        return array_search($this->warmUpSweater, self::$clothingFitSizeChoices, true);
    }

    public function setWarmUpSweater(int $warmUpSweater): self
    {
        $this->warmUpSweater = $warmUpSweater;

        return $this;
    }

    public function getClothingDesiredSize(): ?string
    {
        return $this->clothingDesiredSize;
    }

    public function setClothingDesiredSize(?string $clothingDesiredSize): self
    {
        $this->clothingDesiredSize = $clothingDesiredSize;

        return $this;
    }

    public function getClub(): ?Club
    {
        return $this->club;
    }

    public function setClub(?Club $club): self
    {
        $this->club = $club;

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
