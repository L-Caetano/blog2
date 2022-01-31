<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\OneToMany(targetEntity="App\Entity\Postagem", mappedBy="category")
     */
    private $postagem;

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

    public function __construct()
    {
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
            $postagem->setCategory($this);
        }

        return $this;
    }

    public function removePostagem(Postagem $postagem): self
    {
        if ($this->postagem->removeElement($postagem)) {
            // set the owning side to null (unless already changed)
            if ($postagem->getCategory() === $this) {
                $postagem->setCategory(null);
            }
        }

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
}
