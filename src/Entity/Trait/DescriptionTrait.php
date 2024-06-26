<?php

namespace App\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;

trait DescriptionTrait
{
    #[ORM\Column(length: 255)]
    private ?string $description = null;

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }
}