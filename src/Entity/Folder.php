<?php

namespace App\Entity;

use App\Repository\FolderRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * @ORM\Entity(repositoryClass=FolderRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Folder
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("public")
     */
    private $id;

    /**
     * @Groups("public")
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @Groups("public")
     * @ORM\ManyToOne(targetEntity=Folder::class, inversedBy="subFolders")
     */
    private $subFolder;

    /**
     * @ORM\OneToMany(targetEntity=Folder::class, mappedBy="subFolder")
     */
    private $subFolders;

    /**
     * @ORM\OneToMany(targetEntity=File::class, mappedBy="subFolder")
     */
    private $files;

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps(): void
    {
        $dateTimeNow = new DateTime('now');

        $this->setUpdatedAt($dateTimeNow);

        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt($dateTimeNow);
        }
    }

    public function __toString()
    {
        return $this->name;
    }

    public function __construct()
    {
        $this->subFolders = new ArrayCollection();
        $this->files = new ArrayCollection();
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

    public function getCreatedAt(): ?\DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTime $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTime $updated_at): self
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

    /**
     * @return Collection|File[]
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(File $file): self
    {
        if (!$this->files->contains($file)) {
            $this->files[] = $file;
            $file->setSubFolder($this);
        }

        return $this;
    }

    public function removeFile(File $file): self
    {
        if ($this->files->removeElement($file)) {
            // set the owning side to null (unless already changed)
            if ($file->getSubFolder() === $this) {
                $file->setSubFolder(null);
            }
        }

        return $this;
    }
}
