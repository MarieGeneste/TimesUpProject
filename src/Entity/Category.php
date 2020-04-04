<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
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
     * @ORM\Column(type="string", length=50)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $color;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Word", mappedBy="category")
     */
    private $words;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="categories")
     */
    private $ParentCategory;

    public function __construct()
    {
        $this->cards = new ArrayCollection();
        $this->words = new ArrayCollection();
        $this->ParentCategory = new ArrayCollection();
    }

    /**
     * @return \Ramsey\Uuid\UuidInterface
     */
    public function getId()
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

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

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
     * @return Collection|Word[]
     */
    public function getWords(): Collection
    {
        return $this->words;
    }

    public function addWord(Word $word): self
    {
        if (!$this->words->contains($word)) {
            $this->words[] = $word;
            $word->addCategory($this);
        }

        return $this;
    }

    public function removeWord(Word $word): self
    {
        if ($this->words->contains($word)) {
            $this->words->removeElement($word);
            $word->removeCategory($this);
        }

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getParentCategory(): Collection
    {
        return $this->ParentCategory;
    }

    public function addParentCategory(self $parentCategory): self
    {
        if (!$this->ParentCategory->contains($parentCategory)) {
            $this->ParentCategory[] = $parentCategory;
        }

        return $this;
    }

    public function removeParentCategory(self $parentCategory): self
    {
        if ($this->ParentCategory->contains($parentCategory)) {
            $this->ParentCategory->removeElement($parentCategory);
        }

        return $this;
    }
}
