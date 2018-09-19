<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlaylistRepository")
 */
class Playlist
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @JMS\Groups({"user_playlists", "create_one_playlist", "add_music_to_playlist"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @JMS\Groups({"user_playlists", "create_one_playlist", "add_music_to_playlist"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @JMS\Groups({"user_playlists", "create_one_playlist", "add_music_to_playlist"})
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotBlank()
     * @JMS\Groups({"user_playlists", "create_one_playlist", "add_music_to_playlist"})
     */
    private $is_private;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Favorite", mappedBy="playlist", orphanRemoval=true)
     */
    private $favorites;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Music", inversedBy="playlists")
     * @JMS\Groups({"user_playlists", "add_music_to_playlist"})
     */
    private $musics;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="playlists")
     * @ORM\JoinColumn(nullable=false)
     * @JMS\Groups({"create_one_playlist"})
     */
    private $user;

    public function __construct()
    {
        $this->favorites = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIsPrivate(): ?bool
    {
        return $this->is_private;
    }

    public function setIsPrivate(bool $is_private): self
    {
        $this->is_private = $is_private;

        return $this;
    }

    /**
     * @return Collection|Favorite[]
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(Favorite $favorite): self
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites[] = $favorite;
            $favorite->setPlaylist($this);
        }

        return $this;
    }

    public function removeFavorite(Favorite $favorite): self
    {
        if ($this->favorites->contains($favorite)) {
            $this->favorites->removeElement($favorite);
            // set the owning side to null (unless already changed)
            if ($favorite->getPlaylist() === $this) {
                $favorite->setPlaylist(null);
            }
        }

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
        }

        return $this;
    }

    public function removeMusic(Music $music): self
    {
        if ($this->musics->contains($music)) {
            $this->musics->removeElement($music);
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
