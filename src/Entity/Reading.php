<?php

namespace App\Entity;

use App\Repository\ReadingRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Reading entity
 * 

 * @ORM\Entity(repositoryClass=ReadingRepository::class)
 */
class Reading
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"get_item_reading"})
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"get_item_reading"})
     */
    private $addedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Book::class, inversedBy="readings")
     * @ORM\JoinColumn(nullable=false, name="book_isbn", referencedColumnName="isbn"))
     * @Groups({"get_item_reading"})
     */
    private $book;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="readings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddedAt(): ?\DateTimeInterface
    {
        return $this->addedAt;
    }

    public function setAddedAt(\DateTimeInterface $addedAt): self
    {
        $this->addedAt = $addedAt;

        return $this;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): self
    {
        $this->book = $book;

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
}
