<?php

namespace App\Entity;

use App\Repository\PostagemRepository;
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $yes;

    /**
     * @ORM\Column(type="string", length=800)
     */
    private $descricao;

    /**
     * @ORM\Column(type="string", length=300, nullable=true)
     */
    private $imagem;

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

    public function getYes(): ?string
    {
        return $this->yes;
    }

    public function setYes(?string $yes): self
    {
        $this->yes = $yes;

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
}
