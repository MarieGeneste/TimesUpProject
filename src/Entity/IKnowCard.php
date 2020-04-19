<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IKnowCardRepository")
 */
class IKnowCard extends DefaultEntity
{
    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Response", inversedBy="iKnowCard", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $response;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstIndication;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $secondIndication;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $thirdIndication;

    public function getResponse(): ?Response
    {
        return $this->response;
    }

    public function setResponse(Response $response): self
    {
        $this->response = $response;

        return $this;
    }

    public function getFirstIndication(): ?string
    {
        return $this->firstIndication;
    }

    public function setFirstIndication(string $firstIndication): self
    {
        $this->firstIndication = $firstIndication;

        return $this;
    }

    public function getSecondIndication(): ?string
    {
        return $this->secondIndication;
    }

    public function setSecondIndication(string $secondIndication): self
    {
        $this->secondIndication = $secondIndication;

        return $this;
    }

    public function getThirdIndication(): ?string
    {
        return $this->thirdIndication;
    }

    public function setThirdIndication(string $thirdIndication): self
    {
        $this->thirdIndication = $thirdIndication;

        return $this;
    }
}
