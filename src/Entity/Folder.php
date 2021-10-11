<?php

namespace App\Entity;

use App\Repository\FolderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FolderRepository::class)
 */
class Folder
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
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\ManyToOne(targetEntity=Folder::class, inversedBy="subFolders")
     */
    private $subFolder;

    /**
     * @ORM\OneToMany(targetEntity=Folder::class, mappedBy="subFolder")
     */
    private $subFolders;

    public function __construct()
    {
        $this->subFolders = new ArrayCollection();
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getSubFolder(): ?self
    {
        return $this->subFolder;
    }

    public function setSubFolder(?self $subFolder): self
    {
        $this->subFolder = $subFolder;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getSubFolders(): Collection
    {
        return $this->subFolders;
    }

    public function addSubFolder(self $subFolder): self
    {
        if (!$this->subFolders->contains($subFolder)) {
            $this->subFolders[] = $subFolder;
            $subFolder->setSubFolder($this);
        }

        return $this;
    }

    public function removeSubFolder(self $subFolder): self
    {
        if ($this->subFolders->removeElement($subFolder)) {
            // set the owning side to null (unless already changed)
            if ($subFolder->getSubFolder() === $this) {
                $subFolder->setSubFolder(null);
            }
        }

        return $this;
    }
}
