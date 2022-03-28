<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PersonneRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[UniqueEntity(
        fields: "email",
        message: "L'email que vous avez indiqué est déjà utilisé"
    )]
#[ORM\Entity(repositoryClass: PersonneRepository::class)]
class Personne implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 50)]
    private $nom;

    #[ORM\Column(type: 'string', length: 50)]
    private $prenom;

    #[ORM\Column(type: 'string', length: 255)]
    private $password;

    public $confirm_password;

    #[ORM\Column(type: 'integer')]
    private $phone;

    #[ORM\Column(type: 'string', length: 255)]
    private $email;

    #[ORM\Column(type: 'boolean')]
    private $isAdmin;

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\OneToOne(mappedBy: 'personne', targetEntity: Images::class, cascade: ['persist', 'remove'])]
    private $image;

    #[ORM\OneToMany(mappedBy: 'personne', targetEntity: Annonce::class, orphanRemoval: true)]
    private $annonces;

    #[ORM\ManyToMany(targetEntity: Annonce::class, inversedBy: 'personnes_favories')]
    private $favories;


    public function __construct()
    {
        $this->isAdmin = false;
        $this->annonces = new ArrayCollection();
        $this->favories = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(int $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getIsAdmin(): ?bool
    {
        return $this->isAdmin;
    }

    public function setIsAdmin(bool $isAdmin): self
    {
        $this->isAdmin = $isAdmin;

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

     /**
     * The public representation of the user (e.g. a username, an email address, etc.)
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }


    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('password', new Assert\Length([
            'min' => 12,
            'max' => 255,
            'minMessage' => 'Votre mot de passe doit possèder au moins {{ limit }} caractères ',
            'maxMessage' => 'Votre mot de passe ne peut pas dépasser {{ limit }} caractères',
        ]));

        $metadata->addPropertyConstraint('confirm_password', new Assert\EqualTo([
            'propertyPath'=>"password",
            'message' => "N'avez pas tapé le même mot de passe",
        ]));

        $metadata->addPropertyConstraint('email', new Assert\Email([
            'message' => "L'adresse email {{ value }} n'est pas valide.",
        ]));

        $metadata->addPropertyConstraint('nom', new Assert\Regex([
            'pattern' => '/\d/',
            'match' => false,
            'message' => 'Votre nom ne peut pas avoir de nombre',
        ]));

        $metadata->addPropertyConstraint('prenom', new Assert\Regex([
            'pattern' => '/\d/',
            'match' => false,
            'message' => 'Votre prénom ne peut pas avoir de nombre',
        ]));

        

        $metadata->addPropertyConstraint('password', new Assert\Regex([
            'pattern'=> '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,255}$/',
            'message' => "Le mot de passe doit avoir minimum 1 minuscule, 1 majuscule et 1 chiffre"
        ]));
        
    }

    public function getImage(): ?Images
    {
        return $this->image;
    }

    public function setImage(?Images $image): self
    {
        // unset the owning side of the relation if necessary
        if ($image === null && $this->image !== null) {
            $this->image->setPersonne(null);
        }

        // set the owning side of the relation if necessary
        if ($image !== null && $image->getPersonne() !== $this) {
            $image->setPersonne($this);
        }

        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|Annonce[]
     */
    public function getAnnonces(): Collection
    {
        return $this->annonces;
    }

    public function addAnnonce(Annonce $annonce): self
    {
        if (!$this->annonces->contains($annonce)) {
            $this->annonces[] = $annonce;
            $annonce->setPersonne($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonce $annonce): self
    {
        if ($this->annonces->removeElement($annonce)) {
            // set the owning side to null (unless already changed)
            if ($annonce->getPersonne() === $this) {
                $annonce->setPersonne(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Annonce[]
     */
    public function getFavories(): Collection
    {
        return $this->favories;
    }

    public function addFavories(Annonce $favories): self
    {
        if (!$this->favories->contains($favories)) {
            $this->favories[] = $favories;
        }

        return $this;
    }

    public function removeFavories(Annonce $favories): self
    {
        $this->favories->removeElement($favories);

        return $this;
    }
}
