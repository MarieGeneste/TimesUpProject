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
     * @ORM\OneToOne(targetEntity="App\Entity\BlueCard", mappedBy="content", cascade={"persist", "remove"})
     */
    private $blueCard;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\YellowCard", mappedBy="content", cascade={"persist", "remove"})
     */
    private $yellowCard;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\IKnowCard", mappedBy="response", cascade={"persist", "remove"})
     */
    private $iKnowCard;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\GameMode", mappedBy="responses")
     */
    private $games;

    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->yellowCard = null;
        $this->blueCard = null;
        $this->games = new ArrayCollection();
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

    public function getBlueCard(): ?BlueCard
    {
        return $this->blueCard;
    }

    public function setBlueCard(?BlueCard $blueCard, $action = null): self
    {
        if ($action == "remove") {
            $this->blueCard = null;
    
            if ($blueCard->getContent() !== null) {
                $blueCard->setContent(null);
            }
        } else {
            $this->blueCard = $blueCard;
    
            // set (or unset) the owning side of the relation if necessary
            $newContent = null === $blueCard ? null : $this;
            if ($blueCard->getContent() !== $newContent) {
                $blueCard->setContent($newContent);
            }
        }

        return $this;
    }

    public function getYellowCard(): ?YellowCard
    {
        return $this->yellowCard;
    }

    public function setYellowCard(?YellowCard $yellowCard, $action = null): self
    {
        if ($action == "remove") {
            $this->yellowCard = null;
    
            if ($yellowCard->getContent() !== null) {
                $yellowCard->setContent(null);
            }
        } else {
            $this->yellowCard = $yellowCard;

            // set (or unset) the owning side of the relation if necessary
            $newContent = null === $yellowCard ? null : $this;
            if ($yellowCard->getContent() !== $newContent) {
                $yellowCard->setContent($newContent);
            }
        }

        return $this;
    }

    public function getIKnowCard(): ?IKnowCard
    {
        return $this->iKnowCard;
    }

    public function setIKnowCard(IKnowCard $iKnowCard): self
    {
        $this->iKnowCard = $iKnowCard;

        // set the owning side of the relation if necessary
        if ($iKnowCard->getResponse() !== $this) {
            $iKnowCard->setResponse($this);
        }

        return $this;
    }

    /**
     * @return Collection|GameMode[]
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(GameMode $game): self
    {
        if (!$this->games->contains($game)) {
            $this->games[] = $game;
            $game->addResponse($this);
        }

        return $this;
    }

    public function removeGame(GameMode $game): self
    {
        if ($this->games->contains($game)) {
            $this->games->removeElement($game);
            $game->removeResponse($this);
        }

        return $this;
    }
}
