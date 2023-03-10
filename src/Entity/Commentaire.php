<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $comment = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeInterface $dateHeur = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Remplir le champ user")]

    private ?string $user = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbLike = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbDislike = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    #[ORM\JoinColumn(nullable: false, onDelete:'CASCADE')]
    private ?Actualite $blog = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getDateHeur(): ?\DateTimeInterface
    {
        return $this->dateHeur;
    }

    public function setDateHeur(\DateTimeInterface $dateHeur): self
    {
        $this->dateHeur = $dateHeur;

        return $this;
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function setUser(string $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getNbLike(): ?int
    {
        return $this->nbLike;
    }

    public function setNbLike(?int $nbLike): self
    {
        $this->nbLike = $nbLike;

        return $this;
    }

    public function getNbDislike(): ?int
    {
        return $this->nbDislike;
    }

    public function setNbDislike(?int $nbDislike): self
    {
        $this->nbDislike = $nbDislike;

        return $this;
    }

    public function getBlog(): ?Actualite
    {
        return $this->blog;
    }

    public function setBlog(?Actualite $blog): self
    {
        $this->blog = $blog;

        return $this;
    }
    public function __toString()
    {
        return $this->comment;
    }
}
