<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use PhpParser\Node\Expr\Match_;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    #[ORM\Column(length: 255)]
    private ?string $user = null;

    #[ORM\Column(length: 255)]
    private ?string $comment = null;

    #[ORM\Column(nullable: true)]
    private ?int $Like = null;



    public function getUser(): ?string
    {
        return $this->user;
    }

    public function setUser(string $user): self
    {
        $this->user = $user;

        return $this;
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

    public function getLike(): ?int
    {
        return $this->Like;
    }

    public function setLike(?int $nbLike): self
    {
        $this->Like = $nbLike;

        return $this;
    }
}
