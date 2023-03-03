<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateTime = null;

    #[ORM\ManyToOne(inversedBy: 'games')]
    private ?Team $team1 = null;

    #[ORM\ManyToOne]
    private ?Team $team2 = null;

    #[ORM\Column(nullable: true)]
    private ?int $scoreTeam1 = null;

    #[ORM\Column(nullable: true)]
    private ?int $scoreTeam2 = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateTime(): ?\DateTimeInterface
    {
        return $this->dateTime;
    }

    public function setDateTime(\DateTimeInterface $dateTime): self
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    public function getTeam1(): ?Team
    {
        return $this->team1;
    }

    public function setTeam1(?Team $team1): self
    {
        $this->team1 = $team1;

        return $this;
    }

    public function getTeam2(): ?Team
    {
        return $this->team2;
    }

    public function setTeam2(?Team $team2): self
    {
        $this->team2 = $team2;

        return $this;
    }

    public function getScoreTeam1(): ?int
    {
        return $this->scoreTeam1;
    }

    public function setScoreTeam1(int $scoreTeam1): self
    {
        $this->scoreTeam1 = $scoreTeam1;

        return $this;
    }

    public function getScoreTeam2(): ?int
    {
        return $this->scoreTeam2;
    }

    public function setScoreTeam2(int $scoreTeam2): self
    {
        $this->scoreTeam2 = $scoreTeam2;

        return $this;
    }
}
