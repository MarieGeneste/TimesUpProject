<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserProfileRepository")
 * @Vich\Uploadable
 */
class UserProfile extends DefaultEntity
{
    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="userProfile", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * Avatar de l'utilisateur
     * 
     * @Vich\UploadableField(mapping="user_avatar", fileNameProperty="avatarName")
     * 
     * @var File|null
     */
    private $avatarFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default":null})
     *
     * @var string|null
     */
    private $avatarName;

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
        $this->friends = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

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
     * @param  File|null  \Symfony\Component\HttpFoundation\File\UploadedFile $image
     * @param File|UploadedFile $avatarFilr
     * @return Profile
     * @throws \Exception
     */
    public function setAvatarFile($avatarFile): UserProfile
    {
        $this->avatarFile = $avatarFile;

        \dump($this->avatarFile instanceof UploadedFile );
        if ($this->avatarFile instanceof UploadedFile ) {
            \dump($avatarFile);
            $this->setUpdatedAt(new \DateTime('now'));
        }
        // exit;
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
     */ 
    public function setAvatarName($avatarName)
    {
        $this->avatarName = $avatarName;

        return $this;
    }

    public function getUserProfile(): ?UserProfile
    {
        return $this->userProfile;
    }

}
