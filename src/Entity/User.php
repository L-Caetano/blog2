<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{


    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=Imagem::class, mappedBy="Usuario")
     */
    private $Imagens;

    /**
     * @ORM\OneToMany(targetEntity=Category::class, mappedBy="usuario")
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity=Postagem::class, mappedBy="usuario")
     */
    private $postagems;

    public function __construct()
    {
        $this->Imagens = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->postagems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|Imagem[]
     */
    public function getImagens(): Collection
    {
        return $this->Imagens;
    }

    public function addImagen(Imagem $imagen): self
    {
        if (!$this->Imagens->contains($imagen)) {
            $this->Imagens[] = $imagen;
            $imagen->setUsuario($this);
        }

        return $this;
    }

    public function removeImagen(Imagem $imagen): self
    {
        if ($this->Imagens->removeElement($imagen)) {
            // set the owning side to null (unless already changed)
            if ($imagen->getUsuario() === $this) {
                $imagen->setUsuario(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->setUsuario($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getUsuario() === $this) {
                $category->setUsuario(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Postagem[]
     */
    public function getPostagems(): Collection
    {
        return $this->postagems;
    }

    public function addPostagem(Postagem $postagem): self
    {
        if (!$this->postagems->contains($postagem)) {
            $this->postagems[] = $postagem;
            $postagem->setUsuario($this);
        }

        return $this;
    }

    public function removePostagem(Postagem $postagem): self
    {
        if ($this->postagems->removeElement($postagem)) {
            // set the owning side to null (unless already changed)
            if ($postagem->getUsuario() === $this) {
                $postagem->setUsuario(null);
            }
        }

        return $this;
    }
}
