<?php

namespace App\Entity;

use App\Repository\PostagemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PostagemRepository::class)
 */
class Postagem
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $titulo;

    /**
     * @ORM\Column(type="string", length=800)
     */
    private $descricao;

    /**
     * @ORM\Column(type="string", length=300, nullable=true)
     */
    private $imagem;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $autor;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="postagem")
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity=Imagem::class, mappedBy="IdAlbum")
     */
    private $Imagens;

    public function __construct()
    {
        $this->Imagens = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    public function setDescricao(string $descricao): self
    {
        $this->descricao = $descricao;

        return $this;
    }

    public function getImagem(): ?string
    {
        return $this->imagem;
    }

    public function setImagem(?string $imagem): self
    {
        $this->imagem = $imagem;

        return $this;
    }

    public function getAutor(): ?string
    {
        return $this->autor;
    }

    public function setAutor(string $autor): self
    {
        $this->autor = $autor;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
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
            $imagen->addIdAlbum($this);
        }

        return $this;
    }

    public function removeImagen(Imagem $imagen): self
    {
        if ($this->Imagens->removeElement($imagen)) {
            $imagen->removeIdAlbum($this);
        }

        return $this;
    }
}
