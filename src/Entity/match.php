<?php

namespace App\Entity;

use App\Repository\matchRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: matchRepository::class)]
class comm
{

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Remplir le champ dom")]
    private ?string $domicile = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Remplir le champ ext")]
    private ?string $exterieur = null;


    public function getdomicile(): ?string
    {
        return $this->domicile;
    }

    public function setdomicile(string $description): self
    {
        $this->domicile = $description;

        return $this;
    }
    public function getexterieur(): ?string
    {
        return $this->domicile;
    }

    public function setexterieur(string $description): self
    {
        $this->domicile = $description;

        return $this;
    }
}
