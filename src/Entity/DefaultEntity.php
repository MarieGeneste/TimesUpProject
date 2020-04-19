<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(indexes={@ORM\Index(name="created_at_index", columns={"created_at"})})
 */
class DefaultEntity
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
     * @ORM\Column(type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     *
     * @var \DateTimeInterface
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true, options={"default":null})
     *
     * @var \DateTimeInterface|null
     */
    private $updatedAt;


    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }


    /**
     * @return \Ramsey\Uuid\UuidInterface
     */
    public function getId()
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }


    /**
     * @ORM\PrePersist
     */
    public function setTimeStamps(){
        $tps = new \DateTime();

        if($this->getCreatedAt() == null){
            $this->setCreatedAt($tps);
        }
    }

    /**
     * @ORM\PreUpdate
     */
    public function updateTimeStamps(){
        $tps = new \DateTime();

        $this->setUpdatedAt($tps);
        if($this->getCreatedAt() == null){
            $this->setCreatedAt($tps);
        }
    }

}
