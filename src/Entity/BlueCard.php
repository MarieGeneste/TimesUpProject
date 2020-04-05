<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BlueCardRepository")
 */
class BlueCard
{
    /**
     * @var \Ramsey\Uuid\UuidInterface
     * 
     * @ORM\Id
     * @ORM\Column(type="uuid",unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Card", mappedBy="blueContent")
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
     * @return \Ramsey\Uuid\UuidInterface
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Collection|Card[]
     */
    public function getCards(): Collection
    {
        return $this->cards;
    }

    public function addCard(Card $card): self
    {
        if (!$this->cards->contains($card)) {
            $this->cards[] = $card;
            $card->setBlueContent($this);
        }

        return $this;
    }

    public function removeCard(Card $card): self
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
