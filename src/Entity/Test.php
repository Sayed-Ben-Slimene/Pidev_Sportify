<?php

namespace App\Entity;

use App\Repository\TestRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TestRepository::class)]
class Test
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $aaa = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAaa(): ?string
    {
        return $this->aaa;
    }

    public function setAaa(string $aaa): self
    {
        $this->aaa = $aaa;

        return $this;
    }
}
