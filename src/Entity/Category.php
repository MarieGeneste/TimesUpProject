<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category extends DefaultEntity
{
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $color;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Response", mappedBy="category")
     */
    private $responses;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="categories")
     */
    private $ParentCategory;

    public function __construct()
    {
        $this->cards = new ArrayCollection();
        $this->responses = new ArrayCollection();
        $this->ParentCategory = new ArrayCollection();
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
     * @return Collection|Response[]
     */
    public function getResponses(): Collection
    {
        return $this->responses;
    }

    public function addResponse(Response $response): self
    {
        if (!$this->responses->contains($response)) {
            $this->responses[] = $response;
            $response->addCategory($this);
        }

        return $this;
    }

    public function removeResponse(Response $response): self
    {
        if ($this->responses->contains($response)) {
            $this->responses->removeElement($response);
            $response->removeCategory($this);
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
