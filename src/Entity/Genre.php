<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GenreRepository")
 * @UniqueEntity("name")
 * @UniqueEntity("slug")
 */
class Genre
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @JMS\Groups({"all_genres", "one_music"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @JMS\Groups({"all_genres", "one_music", "user_musics"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Slug(fields={"name"})
     * @Assert\NotBlank()
     * @JMS\Groups({"all_genres", "one_music"})
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Image()
     * @JMS\Groups({"all_genres", "one_music"})
     */
    private $photo;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Music", mappedBy="genres")
     * @JMS\Groups({"all_musics_of_one_genre"})
     */
    private $musics;

    public function __construct()
    {
        $this->musics = new ArrayCollection();
    }

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * @return Collection|Music[]
     */
    public function getMusics(): Collection
    {
        return $this->musics;
    }

    public function addMusic(Music $music): self
    {
        if (!$this->musics->contains($music)) {
            $this->musics[] = $music;
            $music->addGenre($this);
        }

        return $this;
    }

    public function removeMusic(Music $music): self
    {
        if ($this->musics->contains($music)) {
            $this->musics->removeElement($music);
            $music->removeGenre($this);
        }

        return $this;
    }
}
