<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;



#[ORM\Entity(repositoryClass: EvenementRepository::class)]
class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank (message:'le  champs de titre doit etre remplis')]
    #[Assert\Length(min: 5, max: 50, minMessage: "Your string must be at least 5 characters long.", maxMessage: "Your string cannot be longer than 50 characters.")]

    private ?string $nomEvenement = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Assert\GreaterThan("today")]
    private ?\DateTimeInterface $dateEvenement = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank (message:'le  champs de titre doit etre remplis')]
    #[Assert\Length(min: 10, max: 100, minMessage: "Your string must be at least 10 characters long.", maxMessage: "Your string cannot be longer than 100 characters.")]
    private ?string $lieuEvenement = null;





    #[ORM\Column(length: 255)]
    private ?string $img = null;

    #[ORM\Column]
    private ?int $capacite = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEvenement(): ?string
    {
        return $this->nomEvenement;
    }

    public function setNomEvenement(string $nomEvenement): self
    {
        $this->nomEvenement = $nomEvenement;

        return $this;
    }

    public function getDateEvenement(): ?\DateTimeInterface
    {
        return $this->dateEvenement;
    }

    public function setDateEvenement(\DateTimeInterface $dateEvenement): self
    {
        $this->dateEvenement = $dateEvenement;

        return $this;
    }

    public function getLieuEvenement(): ?string
    {
        return $this->lieuEvenement;
    }

    public function setLieuEvenement(string $lieuEvenement): self
    {
        $this->lieuEvenement = $lieuEvenement;

        return $this;
    }

    

 

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(string $img): self
    {
        $this->img = $img;

        return $this;
    }

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(int $capacite): self
    {
        $this->capacite = $capacite;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function __toString()
    {
        return $this->nomEvenement;
    }
}
