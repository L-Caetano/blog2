<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
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
    private $name;


    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $image;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $creation_date;

    /**
     * @ORM\Column(type="datetime")
     */
    private $update_date;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="categories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;

    /**
     * @ORM\ManyToMany(targetEntity=Postagem::class, inversedBy="categories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $postagem;

    //Se for falso é porque foi criado por user normal e nao vai aparecer para todo mundo somente para o usuario que criou
    /**
     * @ORM\Column(type="boolean")
     */
    private $public;


    public function __construct()
    {   

      
        if($this->creation_date == null){
            $this->creation_date = new \DateTime();
        }
        if($this->update_date == null){
            $this->update_date = new \DateTime();
        }
        $this->postagem = new ArrayCollection();
    }
    
    public function getId(): ?int
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


    public function __toString(): string
    {
        // TODO: Implement __toString() method.
        return $this->name;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creation_date;
    }

    public function setCreationDate(\DateTimeInterface $creation_date): self
    {
        $this->creation_date = $creation_date;

        return $this;
    }
    public function prePersist()
    {

        $this->creation_date = new \DateTime();
    }
    

    /**
     * Get the value of update_date
     */ 
    public function getUpdateDate()
    {
        return $this->update_date;
    }

    /**
     * Set the value of update_date
     *
     * @return  self
     */ 
    public function setUpdateDate($update_date)
    {
        $this->update_date = $update_date;

        return $this;
    }
    public function preUpdate()
    {
        $this->update_date = new \DateTime();
    }

    /**
     * Get the value of image
     */ 
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set the value of image
     *
     * @return  self
     */ 
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    public function getUsuario(): ?User
    {
        return $this->usuario;
    }

    public function setUsuario(?User $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * @return Collection|Postagem[]
     */
    public function getPostagem(): Collection
    {
        return $this->postagem;
    }

    public function addPostagem(Postagem $postagem): self
    {
        if (!$this->postagem->contains($postagem)) {
            $this->postagem[] = $postagem;
        }

        return $this;
    }

    public function removePostagem(Postagem $postagem): self
    {
        $this->postagem->removeElement($postagem);

        return $this;
    }

    public function getPublic(): ?bool
    {
        return $this->public;
    }

    public function setPublic(bool $public): self
    {
        $this->public = $public;

        return $this;
    }
}
