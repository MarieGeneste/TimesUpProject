<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Card", mappedBy="yellowCategories")
     */
    private $yellowCards;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Card", mappedBy="blueCategories")
     */
    private $blueCards;

    public function __construct()
    {
        $this->cards = new ArrayCollection();
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

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Card[]
     */
    public function getYellowCards(): Collection
    {
        return $this->yellowCards;
    }

    public function addYellowCard(Card $card): self
    {
        if (!$this->yellowCards->contains($card)) {
            $this->yellowCards[] = $card;
            $card->addYellowCategory($this);
        }

        return $this;
    }

    public function removeYellowCard(Card $card): self
    {
        if ($this->yellowCards->contains($card)) {
            $this->yellowCards->removeElement($card);
            $card->removeYellowCategory($this);
        }

        return $this;
    }

    /**
     * @return Collection|Card[]
     */
    public function getBlueCards(): Collection
    {
        return $this->blueCards;
    }

    public function addBlueCard(Card $card): self
    {
        if (!$this->blueCards->contains($card)) {
            $this->blueCards[] = $card;
            $card->addBlueCategory($this);
        }

        return $this;
    }

    public function removeBlueCard(Card $card): self
    {
        if ($this->blueCards->contains($card)) {
            $this->blueCards->removeElement($card);
            $card->removeBlueCategory($this);
        }

        return $this;
    }
}
