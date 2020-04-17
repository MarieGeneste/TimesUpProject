<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="Il existe déja un compte avec cette adresse email")
 * @UniqueEntity(fields={"username"}, message="Ce nom d'utilisateur n'est pas disponible")
 */
class User implements UserInterface
{
    /**
     * @var \Ramsey\Uuid\UuidInterface
     * 
     * @ORM\Id
     * @ORM\Column(type="uuid",unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * Avatar de l'utilisateur
     * 
     * @Vich\UploadableField(mapping="user_avatar", fileNameProperty="avatarName")
     * 
     * @var File|null
     */
    private $avatarFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string|null
     */
    private $avatarName;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\Length(min=8, minMessage="Votre mot de passe doit contenir au minimum 8 caractères")
     */
    private $password;

    /**
     * @Assert\NotBlank(message="Vous devez saisir un mot de passe", allowNull=false)
     * @Assert\Length(min=8, minMessage="Votre mot de passe doit contenir au minimum 8 caractères")
     */
    private $plain_password;

    /**
     * @Assert\EqualTo(propertyPath="plain_password", message="Le mot de passe de confirmation doit être identique à votre mot de passe")
     */
    private $confirm_password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $activation_token;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="users", cascade={"persist", "remove"})
     */
    private $friends;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $friendRequest = [];


    public function __construct()
    {
        $this->isActive = false;
        $this->roles = ["ROLE_USER"];
        $this->friends = new ArrayCollection();
        $this->users = new ArrayCollection();
    }


    /**
     * @return \Ramsey\Uuid\UuidInterface
     */
    public function getId()
    {
        return $this->id;
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

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
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
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getConfirmPassword(): ?string
    {
        return $this->confirm_password;
    }

    public function setConfirmPassword(?string $confirm_password): self
    {
        $this->confirm_password = $confirm_password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plain_password;
    }

    public function setPlainPassword(?string $plain_password): self
    {
        $this->plain_password = $plain_password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getActivationToken(): ?string
    {
        return $this->activation_token;
    }

    public function setActivationToken(?string $activation_token): self
    {
        $this->activation_token = $activation_token;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getFriends(): Collection
    {
        return $this->friends;
    }

    public function addFriend(self $friend): self
    {
        if (!$this->friends->contains($friend)) {
            $this->friends[] = $friend;
            $friend->addFriend($this);
        }

        return $this;
    }

    public function removeFriend(self $friend): self
    {
        if ($this->friends->contains($friend)) {
            $this->friends->removeElement($friend);
            $friend->removeFriend($this);
        }

        return $this;
    }

    public function getFriendRequests(): ?array
    {
        return $this->friendRequest;
    }

    public function addFriendRequest(?string $friendRequest)
    {
        array_push($this->friendRequest, $friendRequest);

        return $this;
    }

    public function removeFriendRequest($friendRequest)
    {
        $keyFound = array_search($friendRequest, $this->getFriendRequests());
        if ($keyFound !== false) {
            unset($this->friendRequest[$keyFound]);
        }
        return $this;
    }

    /**
     * @return  File|null
     */ 
    public function getAvatarFile()
    {
        return $this->avatarFile;
    }

    /**
     * @param  File|null  $avatarFile  Avatar de l'utilisateur
     *
     * @return  self
     */ 
    public function setAvatarFile($avatarFile)
    {
        $this->avatarFile = $avatarFile;

        return $this;
    }

    /**
     * @return  string|null
     */ 
    public function getAvatarName()
    {
        return $this->avatarName;
    }

    /**
     * @param  string|null  $avatarName
     *
     * @return  self
     */ 
    public function setAvatarName($avatarName)
    {
        $this->avatarName = $avatarName;

        return $this;
    }
}
