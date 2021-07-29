<?php

namespace App\Entity\Traits;

trait PhoneTrait
{
    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected ?string $phone = null;

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     * @return $this
     */
    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }
}
