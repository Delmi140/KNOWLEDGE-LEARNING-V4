<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use DateTime;
use DateInterval;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $delivery_address = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tokenRegistration = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $tokenRegistrationLifeTime = null;

    #[ORM\Column]
    private bool $isVerified = false;




    #[ORM\ManyToMany(targetEntity: Cursus::class)]
    #[ORM\JoinTable(name: 'user_cursus')]
    private Collection $purchasedCursuses;

    #[ORM\ManyToMany(targetEntity: Lessons::class)]
    #[ORM\JoinTable(name: 'user_lessons')]
    private Collection $purchasedLessons;

    public function __construct()
    {
        $this->createdAt = new DateTime('now');
        $this->isVerified = false;
        $this->tokenRegistrationLifeTime = (new DateTime('now'))->add (new DateInterval("P1D"));
        $this->purchasedCursuses = new ArrayCollection();
        $this->purchasedLessons = new ArrayCollection();
        
        
    }




    

    public function __toString()
    {
        return $this->delivery_address;
        return $this->name;
       

    }

    public function getPurchasedLessons(): Collection
    {
    return $this->purchasedLessons;
    }

    public function getPurchasedCursuses(): Collection
    {
        return $this->purchasedCursuses;
    }



    public function addPurchasedProduct($product): void
    {
        if ($product instanceof Cursus && !$this->purchasedCursuses->contains($product)) {
            $this->purchasedCursuses->add($product);
        } elseif ($product instanceof Lessons && !$this->purchasedLessons->contains($product)) {
            $this->purchasedLessons->add($product);
        }
    }

    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDeliveryAddress(): ?string
    {
        return $this->delivery_address;
    }

    public function setDeliveryAddress(string $delivery_address): static
    {
        $this->delivery_address = $delivery_address;

        return $this;
    }

    

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getTokenRegistration(): ?string
    {
        return $this->tokenRegistration;
    }

    public function setTokenRegistration(?string $tokenRegistration): static
    {
        $this->tokenRegistration = $tokenRegistration;

        return $this;
    }

    public function getTokenRegistrationLifeTime(): ?\DateTimeInterface
    {
        return $this->tokenRegistrationLifeTime;
    }

    public function setTokenRegistrationLifeTime(\DateTimeInterface $tokenRegistrationLifeTime): static
    {
        $this->tokenRegistrationLifeTime = $tokenRegistrationLifeTime;

        return $this;
    }

    public function isVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function setVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

   
}
