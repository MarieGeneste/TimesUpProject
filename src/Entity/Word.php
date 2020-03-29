<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WordRepository")
 */
class Word
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
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
     * @ORM\Column(type="integer", nullable=true)
     * @param int 1 : Yellow
     * @param int 2 : Blue
     */
    private $color;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="words")
     * @ORM\JoinColumn(nullable=true)
     */
    private $category;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Card", mappedBy="yellowWord", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $card;

    public function __construct()
    {
        $this->category = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getColor(): ?int
    {
        return $this->color;
    }

    /**
     * @param int 1 : Yellow
     * @param int 2 : Blue
     */
    public function setColor(int $color): self
    {
        $this->color = $color;

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

    public function getCard(): ?Card
    {
        return $this->card;
    }

    public function setCard(Card $card): self
    {
        $this->card = $card;

        if ($this->color == 1) {
            // set the owning side of the relation if necessary
            if ($card->getYellowWord() !== $this) {
                $card->setYellowWord($this);
            }
        } elseif ($this->color == 2) {
            // set the owning side of the relation if necessary
            if ($card->getBlueWord() !== $this) {
                $card->setBlueWord($this);
            }
        }


        return $this;
    }
}
