<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
class Team
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private array $playerlist = [];

    #[ORM\OneToMany(mappedBy: 'team1', targetEntity: Game::class)]
    private Collection $games;

    #[ORM\OneToMany(mappedBy: 'team2', targetEntity: UGame::class)]
    private Collection $ugames;

    public function __construct()
    {
        $this->games = new ArrayCollection();
        $this->ugames = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPlayerList(): array
    {
        return $this->playerlist;
    }

    public function setPlayerList(?array $playerList): self
    {
        $this->playerlist = $playerList;

        return $this;
    }

    // public function getImage(): ?string
    // {
    //     return $this->image;
    // }
    // public function setImage(?string $image): self
    // {
    //     $this->image = $image;

    //     return $this;
    // }

    /**
     * @return Collection<int, Game>
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): self
    {
        if (!$this->games->contains($game)) {
            $this->games->add($game);
            $game->setTeam1($this);
        }

        return $this;
    }
    public function removeGame(Game $game): self
    {
        if ($this->games->removeElement($game)) {
            // set the owning side to null (unless already changed)
            if ($game->getTeam1() === $this) {
                $game->setTeam1(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UGame>
     */
    public function getUgames(): Collection
    {
        return $this->ugames;
    }

    public function addUgame(UGame $game): self
    {
        if (!$this->ugames->contains($game)) {
            $this->ugames->add($game);
            $game->setTeam1($this);
        }

        return $this;
    }
    public function removeUgame(Game $game): self
    {
        if ($this->ugames->removeElement($game)) {
            // set the owning side to null (unless already changed)
            if ($game->getTeam1() === $this) {
                $game->setTeam1(null);
            }
        }

        return $this;
    }
}
