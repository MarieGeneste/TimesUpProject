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
     * @ORM\ManyToOne(targetEntity="App\Entity\Edition", inversedBy="cards")
     * @ORM\JoinColumn(nullable=false)
     */
    private $edition;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Word", inversedBy="card", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $yellowWord;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Word", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $blueWord;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->words = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getYellowWord(): ?Word
    {
        return $this->yellowWord;
    }

    public function setYellowWord(Word $yellowWord): self
    {
        $this->yellowWord = $yellowWord;

        return $this;
    }

    public function getBlueWord(): ?Word
    {
        return $this->blueWord;
    }

    public function setBlueWord(Word $blueWord): self
    {
        $this->blueWord = $blueWord;

        return $this;
    }
}
