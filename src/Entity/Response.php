<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ResponseRepository")
 */
class Response
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
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="responses")
     * @ORM\JoinColumn(nullable=true)
     */
    private $category;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\YellowCard", mappedBy="content", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $yellowCard;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\BlueCard", mappedBy="content", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $blueCard;

    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->yellowCard = null;
        $this->blueCard = null;
    }

    /**
     * @return \Ramsey\Uuid\UuidInterface
     */
    public function getId()
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->category->contains($category)) {
            $this->category->removeElement($category);
        }

        return $this;
    }

    public function getYellowCard(): ?YellowCard
    {
        return $this->yellowCard;
    }

    public function setYellowCard(YellowCard $yellowCard): self
    {
        $this->yellowCard = $yellowCard;

        // set the owning side of the relation if necessary
        if ($yellowCard->getContent() !== $this) {
            $yellowCard->setContent($this);
        }

        return $this;
    }

    public function getBlueCard(): ?BlueCard
    {
        return $this->blueCard;
    }

    public function setBlueCard(BlueCard $blueCard): self
    {
        $this->blueCard = $blueCard;

        // set the owning side of the relation if necessary
        if ($blueCard->getContent() !== $this) {
            $blueCard->setContent($this);
        }

        return $this;
    }
}
