<?php

namespace App\Entity;

use App\Repository\VotesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VotesRepository::class)]
class Votes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private ?UGame $ugame = null;

    #[ORM\Column(nullable: true)]
    private ?int $scoreteam1 = null;

    #[ORM\Column(nullable: true)]
    private ?int $scoreteam2 = null;


    public function getUgame(): ?UGame
    {
        return $this->ugame;
    }

    public function setUgame(?UGame $ugame): self
    {
        $this->ugame = $ugame;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScoreTeam1(): ?int
    {
        return $this->scoreteam1;
    }

    public function setScoreTeam1(int $scoreTeam1): self
    {
        $this->scoreteam1 = $scoreTeam1;

        return $this;
    }

    public function getScoreTeam2(): ?int
    {
        return $this->scoreteam2;
    }

    public function setScoreTeam2(int $scoreTeam2): self
    {
        $this->scoreteam2 = $scoreTeam2;

        return $this;
    }
}
