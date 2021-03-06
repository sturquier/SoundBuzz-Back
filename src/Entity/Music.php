<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MusicRepository")
 */
class Music
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @JMS\Groups({"one_music", "all_musics_of_one_genre", "one_artist", "create_one_music", "admin_all_musics", "user_musics", "user_playlists", "add_comment", "download_music", "add_music_to_playlist", "get_playlist_details"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @JMS\Groups({"one_music", "all_musics_of_one_genre", "one_artist", "create_one_music", "admin_all_musics", "user_musics", "user_playlists", "add_comment", "download_music", "add_music_to_playlist", "get_playlist_details"})
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @JMS\Groups({"one_music", "all_musics_of_one_genre", "one_artist", "create_one_music", "user_musics"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\File(
     *      maxSize = "20M",
     *      mimeTypes={ "audio/mp3", "audio/mpeg","audio/mp4" }
     * )
     * @JMS\Groups({"one_music", "all_musics_of_one_genre", "one_artist", "create_one_music", "user_musics"})
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Image()
     * @JMS\Groups({"one_music", "all_musics_of_one_genre", "one_artist", "create_one_music", "user_musics"})
     */
    private $photo;

    /**
     * @ORM\Column(type="boolean", options={"default": 0})
     * @JMS\Groups({"one_music", "all_musics_of_one_genre", "one_artist", "create_one_music", "admin_all_musics", "user_musics"})
     */
    private $is_explicit = 0;

    /**
     * @ORM\Column(type="boolean", options={"default": 1})
     * @JMS\Groups({"one_music", "all_musics_of_one_genre", "one_artist", "create_one_music", "admin_all_musics", "user_musics"})
     */
    private $downloadable = true;

    /**
     * @ORM\Column(type="string", length=255)
     * @JMS\Groups({"one_music", "all_musics_of_one_genre", "one_artist", "create_one_music", "user_musics", "download_music"})
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     * @JMS\Groups({"one_music", "all_musics_of_one_genre", "one_artist", "create_one_music", "admin_all_musics", "user_musics"})
     */
    private $transfer_at;

    /**
     * @ORM\Column(type="float", options={"default": 0})
     * @JMS\Groups({"one_music", "all_musics_of_one_genre", "one_artist", "create_one_music", "user_musics", "user_playlists"})
     */
    private $duration = 0;

    /**
     * @ORM\Column(type="boolean", options={"default": 1})
     * @JMS\Groups({"one_music", "all_musics_of_one_genre", "one_artist", "create_one_music", "admin_all_musics", "user_musics"})
     */
    private $is_active = true;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Like", mappedBy="music", orphanRemoval=true)
     */
    private $likes;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Playlist", mappedBy="musics")
     */
    private $playlists;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="music", orphanRemoval=true)
     * @JMS\Groups({"one_music"})
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Listen", mappedBy="music", orphanRemoval=true)
     */
    private $listens;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="musics")
     * @ORM\JoinColumn(nullable=true)
     * @JMS\Groups({"one_music"})
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Genre", inversedBy="musics")
     * @JMS\Groups({"one_music", "user_musics"})
     */
    private $genres;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Artist", mappedBy="musics")
     * @JMS\Groups({"one_music", "all_musics_of_one_genre", "user_musics", "get_playlist_details"})
     */
    private $artists;

    /**
     * @ORM\Column(type="integer", options={"default": 0})
     * @JMS\Groups({"one_music", "create_one_music", "user_musics", "download_music"})
     */
    private $downloads = 0;

    public function __construct()
    {
        $this->likes = new ArrayCollection();
        $this->playlists = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->listens = new ArrayCollection();
        $this->genres = new ArrayCollection();
        $this->artists = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file): self
    {
        $this->file = $file;

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

    public function getIsExplicit(): ?bool
    {
        return $this->is_explicit;
    }

    public function setIsExplicit(bool $is_explicit): self
    {
        $this->is_explicit = $is_explicit;

        return $this;
    }

    public function getDownloadable(): ?bool
    {
        return $this->downloadable;
    }

    public function setDownloadable(bool $downloadable): self
    {
        $this->downloadable = $downloadable;

        return $this;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    public function setCreatedAt(string $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getTransferAt(): ?\DateTimeInterface
    {
        return $this->transfer_at;
    }

    public function setTransferAt(\DateTimeInterface $transfer_at): self
    {
        $this->transfer_at = $transfer_at;

        return $this;
    }

    public function getDuration(): ?float
    {
        return $this->duration;
    }

    public function setDuration(float $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): self
    {
        $this->is_active = $is_active;

        return $this;
    }

    /**
     * @return Collection|Like[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setMusic($this);
        }

        return $this;
    }

    public function removeLike(Like $like): self
    {
        if ($this->likes->contains($like)) {
            $this->likes->removeElement($like);
            // set the owning side to null (unless already changed)
            if ($like->getMusic() === $this) {
                $like->setMusic(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Playlist[]
     */
    public function getPlaylists(): Collection
    {
        return $this->playlists;
    }

    public function addPlaylist(Playlist $playlist): self
    {
        if (!$this->playlists->contains($playlist)) {
            $this->playlists[] = $playlist;
            $playlist->addMusic($this);
        }

        return $this;
    }

    public function removePlaylist(Playlist $playlist): self
    {
        if ($this->playlists->contains($playlist)) {
            $this->playlists->removeElement($playlist);
            $playlist->removeMusic($this);
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setMusic($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getMusic() === $this) {
                $comment->setMusic(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Listen[]
     */
    public function getListens(): Collection
    {
        return $this->listens;
    }

    public function addListen(Listen $listen): self
    {
        if (!$this->listens->contains($listen)) {
            $this->listens[] = $listen;
            $listen->setMusic($this);
        }

        return $this;
    }

    public function removeListen(Listen $listen): self
    {
        if ($this->listens->contains($listen)) {
            $this->listens->removeElement($listen);
            // set the owning side to null (unless already changed)
            if ($listen->getMusic() === $this) {
                $listen->setMusic(null);
            }
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

    /**
     * @return Collection|Genre[]
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): self
    {
        if (!$this->genres->contains($genre)) {
            $this->genres[] = $genre;
        }

        return $this;
    }

    public function removeGenre(Genre $genre): self
    {
        if ($this->genres->contains($genre)) {
            $this->genres->removeElement($genre);
        }

        return $this;
    }

    /**
     * @return Collection|Artist[]
     */
    public function getArtists(): Collection
    {
        return $this->artists;
    }

    public function addArtist(Artist $artist): self
    {
        if (!$this->artists->contains($artist)) {
            $this->artists[] = $artist;
            $artist->addMusic($this);
        }

        return $this;
    }

    public function removeArtist(Artist $artist): self
    {
        if ($this->artists->contains($artist)) {
            $this->artists->removeElement($artist);
            $artist->removeMusic($this);
        }

        return $this;
    }

    public function getDownloads(): ?int
    {
        return $this->downloads;
    }

    public function setDownloads(int $downloads): self
    {
        $this->downloads = $downloads;

        return $this;
    }

    public function incrementDownloads(): self
    {
        $this->downloads ++;

        return $this;
    }
}
