<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CardRepository")
 */
class Card
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Edition", inversedBy="cards")
     * @ORM\JoinColumn(nullable=false)
     */
    private $edition;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\YellowCard", inversedBy="cards")
     * @ORM\JoinColumn(nullable=false)
     */
    private $yellowContent;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\BlueCard", inversedBy="cards")
     * @ORM\JoinColumn(nullable=false)
     */
    private $blueContent;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    /**
     * @return \Ramsey\Uuid\UuidInterface
     */
    public function getId()
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

    public function getYellowContent(): ?YellowCard
    {
        return $this->yellowContent;
    }

    public function setYellowContent(?YellowCard $yellowContent): self
    {
        $this->yellowContent = $yellowContent;

        return $this;
    }

    public function getBlueContent(): ?BlueCard
    {
        return $this->blueContent;
    }

    public function setBlueContent(?BlueCard $blueContent): self
    {
        $this->blueContent = $blueContent;

        return $this;
    }
}
