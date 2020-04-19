<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BlueCardRepository")
 */
class BlueCard extends DefaultEntity
{

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TimesUpCard", mappedBy="blueContent")
     * @ORM\JoinColumn(nullable=true)
     */
    private $cards;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Response", inversedBy="blueCard", cascade={"persist", "remove"})
     */
    private $content;

    // /**
    //  * @ORM\OneToOne(targetEntity="App\Entity\Response", inversedBy="blueCard", cascade={"persist")
    //  * @ORM\JoinColumn(nullable=true)
    //  */
    // private $content;

    public function __construct()
    {
        $this->cards = new ArrayCollection();
    }

    /**
     * @return Collection|TimesUpCard[]
     */
    public function getCards(): Collection
    {
        return $this->cards;
    }

    public function addCard(TimesUpCard $card): self
    {
        if (!$this->cards->contains($card)) {
            $this->cards[] = $card;
            $card->setBlueContent($this);
        }

        return $this;
    }

    public function removeCard(TimesUpCard $card): self
    {
        if ($this->cards->contains($card)) {
            $this->cards->removeElement($card);
            // set the owning side to null (unless already changed)
            if ($card->getBlueContent() === $this) {
                $card->setBlueContent(null);
            }
        }

        return $this;
    }

    public function getContent(): ?Response
    {
        return $this->content;
    }

    public function setContent(?Response $content): self
    {
        $this->content = $content;

        return $this;
    }
}
