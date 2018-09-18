<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("username")
 * @UniqueEntity("email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @JMS\Groups({"auth_token", "add_comment", "admin_all_users", "admin_delete_user"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @JMS\Groups({"auth_token", "admin_all_users", "admin_delete_user"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @JMS\Groups({"auth_token", "admin_all_users", "admin_delete_user"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     * @JMS\Groups({"auth_token", "add_comment", "admin_all_users", "admin_delete_user"})
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @JMS\Groups({"auth_token", "admin_all_users", "admin_delete_user"})
     */
    private $email;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     * @JMS\Groups({"auth_token"})
     */
    private $created_at;

    /**
     * @ORM\Column(type="string", length=255)
     * @JMS\Groups({"auth_token", "admin_all_users"})
     */
    private $role = 'ROLE_USER';

    /**
     * @ORM\Column(type="string", length=255)
     * @JMS\Groups({"auth_token"})
     */
    private $birthday;

    /**
     * @ORM\Column(type="boolean", options={"default": 1})
     * @JMS\Groups({"admin_all_users"})
     */
    private $is_active = true;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Favorite", mappedBy="user", orphanRemoval=true)
     */
    private $favorites;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Like", mappedBy="user", orphanRemoval=true)
     */
    private $likes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Playlist", mappedBy="user", orphanRemoval=true)
     */
    private $playlists;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="user", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Listen", mappedBy="user", orphanRemoval=true)
     */
    private $listens;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Music", mappedBy="user", orphanRemoval=true)
     */
    private $musics;

    public function __construct()
    {
        $this->favorites = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->playlists = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->listens = new ArrayCollection();
        $this->musics = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getRoles()
    {
        return [];
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
        $this->password = null;
    }

    public function getBirthday(): ?string
    {
        return $this->birthday;
    }

    public function setBirthday(string $birthday): self
    {
        $this->birthday = $birthday;

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
            $favorite->setUser($this);
        }

        return $this;
    }

    public function removeFavorite(Favorite $favorite): self
    {
        if ($this->favorites->contains($favorite)) {
            $this->favorites->removeElement($favorite);
            // set the owning side to null (unless already changed)
            if ($favorite->getUser() === $this) {
                $favorite->setUser(null);
            }
        }

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
            $like->setUser($this);
        }

        return $this;
    }

    public function removeLike(Like $like): self
    {
        if ($this->likes->contains($like)) {
            $this->likes->removeElement($like);
            // set the owning side to null (unless already changed)
            if ($like->getUser() === $this) {
                $like->setUser(null);
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
            $playlist->setUser($this);
        }

        return $this;
    }

    public function removePlaylist(Playlist $playlist): self
    {
        if ($this->playlists->contains($playlist)) {
            $this->playlists->removeElement($playlist);
            // set the owning side to null (unless already changed)
            if ($playlist->getUser() === $this) {
                $playlist->setUser(null);
            }
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
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
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
            $listen->setUser($this);
        }

        return $this;
    }

    public function removeListen(Listen $listen): self
    {
        if ($this->listens->contains($listen)) {
            $this->listens->removeElement($listen);
            // set the owning side to null (unless already changed)
            if ($listen->getUser() === $this) {
                $listen->setUser(null);
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
            $music->setUser($this);
        }

        return $this;
    }

    public function removeMusic(Music $music): self
    {
        if ($this->musics->contains($music)) {
            $this->musics->removeElement($music);
            // set the owning side to null (unless already changed)
            if ($music->getUser() === $this) {
                $music->setUser(null);
            }
        }

        return $this;
    }
}
