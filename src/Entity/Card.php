<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CardRepository")
 */
class Card
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
    private $yellowText;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $yellowDescription;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="yellowCards")
     */
    private $yellowCategories;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $blueText;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $blueDescription;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="blueCards")
     */
    private $blueCategories;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Edition", inversedBy="cards")
     * @ORM\JoinColumn(nullable=false)
     */
    private $edition;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="cards")
     */
    private $category;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getYellowText(): ?string
    {
        return $this->yellowText;
    }

    public function setYellowText(string $yellowText): self
    {
        $this->yellowText = $yellowText;

        return $this;
    }

    public function getYellowDescription(): ?string
    {
        return $this->yellowDescription;
    }

    public function setYellowDescription(?string $yellowDescription): self
    {
        $this->yellowDescription = $yellowDescription;

        return $this;
    }

    public function getBlueText(): ?string
    {
        return $this->blueText;
    }

    public function setBlueText(string $blueText): self
    {
        $this->blueText = $blueText;

        return $this;
    }

    public function getBlueDescription(): ?string
    {
        return $this->blueDescription;
    }

    public function setBlueDescription(?string $blueDescription): self
    {
        $this->blueDescription = $blueDescription;

        return $this;
    }

    public function getEdition(): ?Edition
    {
        return $this->edition;
    }

    public function setEdition(?Edition $edition): self
    {
        $this->edition = $edition;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getYellowCategories(): Collection
    {
        return $this->yellowCategories;
    }

    public function addYellowCategory(Category $category): self
    {
        if (!$this->yellowCategories->contains($category)) {
            $this->yellowCategories[] = $category;
        }

        return $this;
    }

    public function removeYellowCategory(Category $category): self
    {
        if ($this->yellowCategories->contains($category)) {
            $this->yellowCategories->removeElement($category);
        }

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getBlueCategories(): Collection
    {
        return $this->blueCategories;
    }

    public function addBlueCategory(Category $category): self
    {
        if (!$this->blueCategories->contains($category)) {
            $this->blueCategories[] = $category;
        }

        return $this;
    }

    public function removeBlueCategory(Category $category): self
    {
        if ($this->blueCategories->contains($category)) {
            $this->blueCategories->removeElement($category);
        }

        return $this;
    }
}
