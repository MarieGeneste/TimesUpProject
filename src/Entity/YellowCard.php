<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\YellowCardRepository")
 */
class YellowCard
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
     * @ORM\OneToMany(targetEntity="App\Entity\TimesUpCard", mappedBy="yellowContent")
     * @ORM\JoinColumn(nullable=true)
     */
    private $cards;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Response", inversedBy="yellowCard", cascade={"persist", "remove"})
     */
    private $content;

    public function __construct()
    {
        $this->cards = new ArrayCollection();
    }

    /**
     * @return \Ramsey\Uuid\UuidInterface
     */
    public function getId()
    {
        return $this->id;
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
            $card->setYellowContent($this);
        }

        return $this;
    }

    public function removeCard(TimesUpCard $card): self
    {
        if ($this->cards->contains($card)) {
            $this->cards->removeElement($card);
            // set the owning side to null (unless already changed)
            if ($card->getYellowContent() === $this) {
                $card->setYellowContent(null);
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
