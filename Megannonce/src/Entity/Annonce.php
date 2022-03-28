<?php

namespace App\Entity;

use App\Repository\AnnonceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[
    ORM\Entity(repositoryClass: AnnonceRepository::class),
    ORM\Table(name:"annonce"),
    ORM\Index(columns:["title","description"], flags:['fulltext']),
]

class Annonce
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'integer')]
    private $price;

    #[ORM\Column(type: 'string', length: 255)]
    private $location;

    #[ORM\Column(type: 'string', length: 255)]
    private $category;

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    #[ORM\Column(type: 'string', length: 255)]
    private $description;

    #[ORM\Column(type: 'string', length: 50)]
    private $etat;

    #[ORM\Column(type: 'boolean')]
    private $isVerify;

    #[ORM\ManyToOne(targetEntity: Personne::class, inversedBy: 'annonces')]
    #[ORM\JoinColumn(nullable: false)]
    private $personne;

    #[ORM\OneToMany(mappedBy: 'annonce', targetEntity: Images::class, cascade: ['persist', 'remove'])]
    private $images;

    #[ORM\ManyToMany(targetEntity: Personne::class, mappedBy: 'favories')]
    private $personnes_favories;



    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->personnes_favories = new ArrayCollection();
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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getIsVerify(): ?bool
    {
        return $this->isVerify;
    }

    public function setIsVerify(bool $isVerify): self
    {
        $this->isVerify = $isVerify;

        return $this;
    }

    public function getPersonne(): ?Personne
    {
        return $this->personne;
    }

    public function setPersonne(?Personne $personne): self
    {
        $this->personne = $personne;

        return $this;
    }

    /**
     * @return Collection|Images[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setAnnonce($this);
        }

        return $this;
    }

    public function removeImage(Images $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getAnnonce() === $this) {
                $image->setAnnonce(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Personne[]
     */
    public function getPersonnesFavories(): Collection
    {
        return $this->personnes_favories;
    }

    public function addPersonnesFavories(Personne $personnesFavories): self
    {
        if (!$this->personnes_favories->contains($personnesFavories)) {
            $this->personnes_favories[] = $personnesFavories;
            $personnesFavories->addFavories($this);
        }

        return $this;
    }

    public function removePersonnesFavories(Personne $personnesFavories): self
    {
        if ($this->personnes_favories->removeElement($personnesFavories)) {
            $personnesFavories->removeFavories($this);
        }

        return $this;
    }
}
