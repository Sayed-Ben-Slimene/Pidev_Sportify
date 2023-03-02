<?php

namespace App\Entity;

use Assert\NotBlank;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProduitsRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ProduitsRepository::class)]
class Produits
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[Groups("produit")]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups("produit")]
    #[Assert\Length( min: 3, minMessage: 'title doit avoir au minimum 3 caracteres',)]
    #[Assert\NotBlank(message: "vous devez mettre le title !!!")]
    
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups("produit")]
    #[Assert\Length( min: 10, max: 1000, minMessage: 'Vous devez decrire plus de details ',)]
    #[NotBlank(message: "vous devez mettre le description !!!")]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups("produit")]
   
    private ?bool $published = null;

    

    #[ORM\Column (type: Types::DECIMAL, precision: 10, scale: '0')]
    #[Groups("produit")]
    #[Assert\Positive(message:" donner un prix réel en dinar")]
    #[Assert\NotBlank(message: "vous devez mettre le prix !!!")]
    private ?float $prix = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[Groups("produit")]
    #[ORM\JoinColumn(nullable: false)]
    
    
    

    private ?Category $category = null;

    #[ORM\Column(length: 255)]
   
    
    private ?string $photo = null;

    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: Panier::class)]
    private Collection $paniers;

    public function __construct()
    {
        $this->paniers = new ArrayCollection();
    }

    

    

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function isPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): self
    {
        $this->published = $published;

        return $this;
    }

   

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * @return Collection<int, Panier>
     */
    public function getPaniers(): Collection
    {
        return $this->paniers;
    }

    public function addPanier(Panier $panier): self
    {
        if (!$this->paniers->contains($panier)) {
            $this->paniers->add($panier);
            $panier->setProduit($this);
        }

        return $this;
    }

    public function removePanier(Panier $panier): self
    {
        if ($this->paniers->removeElement($panier)) {
            // set the owning side to null (unless already changed)
            if ($panier->getProduit() === $this) {
                $panier->setProduit(null);
            }
        }

        return $this;
    }

   

    
}
