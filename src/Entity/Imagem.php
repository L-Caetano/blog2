<?php

namespace App\Entity;

use App\Repository\ImagemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImagemRepository::class)
 */
class Imagem
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Titulo;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\ManyToMany(targetEntity=Postagem::class, inversedBy="Imagens")
     */
    private $IdAlbum;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="Imagens")
     */
    private $Usuario;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Url;

    public function __construct()
    {
        $this->IdAlbum = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->Titulo;
    }

    public function setTitulo(string $Titulo): self
    {
        $this->Titulo = $Titulo;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return Collection|Postagem[]
     */
    public function getIdAlbum(): Collection
    {
        return $this->IdAlbum;
    }

    public function addIdAlbum(Postagem $idAlbum): self
    {
        if (!$this->IdAlbum->contains($idAlbum)) {
            $this->IdAlbum[] = $idAlbum;
        }

        return $this;
    }

    public function removeIdAlbum(Postagem $idAlbum): self
    {
        $this->IdAlbum->removeElement($idAlbum);

        return $this;
    }

    public function getUsuario(): ?User
    {
        return $this->Usuario;
    }

    public function setUsuario(?User $Usuario): self
    {
        $this->Usuario = $Usuario;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->Url;
    }

    public function setUrl(string $Url): self
    {
        $this->Url = $Url;

        return $this;
    }
}
