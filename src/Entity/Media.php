<?php

namespace App\Entity;

use App\Repository\MediaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MediaRepository::class)
 */
class Media
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $caption;

    /**
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="media")
     * @ORM\JoinColumn(nullable=false)
     */
    private $mediaEvent;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="media")
     * @ORM\JoinColumn(nullable=false)
     */
    private $mediaAuthor;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCaption(): ?string
    {
        return $this->caption;
    }

    public function setCaption(string $caption): self
    {
        $this->caption = $caption;

        return $this;
    }

    public function getMediaEvent(): ?Event
    {
        return $this->mediaEvent;
    }

    public function setMediaEvent(?Event $mediaEvent): self
    {
        $this->mediaEvent = $mediaEvent;

        return $this;
    }

    public function getMediaAuthor(): ?User
    {
        return $this->mediaAuthor;
    }

    public function setMediaAuthor(?User $mediaAuthor): self
    {
        $this->mediaAuthor = $mediaAuthor;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }
    
    public function fileExists()
    {
    	return file_exists($this->getUrl());
    }
}
