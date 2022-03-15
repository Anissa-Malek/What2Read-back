<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\InverseJoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Book entity
 * 
 * @ORM\Entity(repositoryClass=BookRepository::class)
 */
class Book
{
    /**
     * @ORM\Id
     * @ORM\Column(name="isbn", type="string")
     * @Groups({"get_collection", "get_item", "get_item_favorite", "get_item_reading", "get_item_book_reviews", "get_collection_suggestion"})
     */
    private $isbn;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get_collection", "get_item", "get_item_favorite", "get_item_reading", "get_item_book_reviews", "get_collection_suggestion"})
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Groups({"get_collection", "get_item", "get_item_book_reviews"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=32)
     * @Groups({"get_collection", "get_item", "get_item_book_reviews"})
     */
    private $publisher;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"get_collection", "get_item", "get_item_book_reviews"})
     */
    private $publicationDate;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get_collection", "get_item", "get_item_favorite", "get_item_reading", "get_item_book_reviews", "get_collection_suggestion"})
     */
    private $cover;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     * @Groups({"get_collection", "get_item", "get_item_favorite", "get_item_reading", "get_item_book_reviews", "get_collection_suggestion"})
     */
    private $subtitle;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"get_collection", "get_item", "get_item_book_reviews"})
     */
    private $matureRating;

    /**
     * Owning Side
     * 
     * @ORM\ManyToMany(targetEntity="Genre", inversedBy="books")
     * @JoinTable(name="book_genre",
     *      joinColumns={@JoinColumn(name="book_isbn", referencedColumnName="isbn")},
     *      inverseJoinColumns={@JoinColumn(name="genre_id", referencedColumnName="id")}
     *      )
     * @Groups({"get_collection", "get_item", "get_item_book_reviews"})
     */
    private $genres;

    /**
     * Owning Side
     * 
     * @ORM\ManyToMany(targetEntity="Author", inversedBy="books")
     * @JoinTable(name="author_book",
     *      joinColumns={@JoinColumn(name="book_isbn", referencedColumnName="isbn")},
     *      inverseJoinColumns={@JoinColumn(name="author_id", referencedColumnName="id")}
     *      )
     * @Groups({"get_collection", "get_item", "get_item_book_reviews"})
     * 
     */
    private $authors;

    /**
     * @ORM\OneToMany(targetEntity=Favorite::class, mappedBy="book")
     */
    private $favorites;

    /**
     * @ORM\OneToMany(targetEntity=Review::class, mappedBy="book", orphanRemoval=true)
     */
    private $reviews;

    /**
     * @ORM\OneToMany(targetEntity=Reading::class, mappedBy="book", orphanRemoval=true)
     */
    private $readings;

    public function __construct()
    {
        $this->genres = new ArrayCollection();
        $this->authors = new ArrayCollection();
        $this->favorites = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->readings = new ArrayCollection();
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): self
    {
        $this->isbn = $isbn;
        
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPublisher(): ?string
    {
        return $this->publisher;
    }

    public function setPublisher(string $publisher): self
    {
        $this->publisher = $publisher;

        return $this;
    }

    public function getPublicationDate(): ?\DateTimeInterface
    {
        return $this->publicationDate;
    }

    public function setPublicationDate(\DateTimeInterface $publicationDate): self
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    public function getCover(): ?string
    {
        return $this->cover;
    }

    public function setCover(string $cover): self
    {
        $this->cover = $cover;

        return $this;
    }

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function setSubtitle(?string $subtitle): self
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    public function getMatureRating(): ?bool
    {
        return $this->matureRating;
    }

    public function setMatureRating(bool $matureRating): self
    {
        $this->matureRating = $matureRating;

        return $this;
    }

    /**
     * @return Collection|Genre[]
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): self
    {
        if (!$this->genres->contains($genre)) {
            $this->genres[] = $genre;
            $genre->addBook($this);
        }

        return $this;
    }

    public function removeGenre(Genre $genre): self
    {
        if ($this->genres->removeElement($genre)) {
            $genre->removeBook($this);
        }

        return $this;
    }

    /**
     * @return Collection|Author[]
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAuthor(Author $author): self
    {
        if (!$this->authors->contains($author)) {
            $this->authors[] = $author;
        }

        return $this;
    }

    public function removeAuthor(Author $author): self
    {
        $this->authors->removeElement($author);

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
            $favorite->setBook($this);
        }

        return $this;
    }

    public function removeFavorite(Favorite $favorite): self
    {
        if ($this->favorites->removeElement($favorite)) {
            // set the owning side to null (unless already changed)
            if ($favorite->getBook() === $this) {
                $favorite->setBook(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Review[]
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setBook($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getBook() === $this) {
                $review->setBook(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Reading[]
     */
    public function getReadings(): Collection
    {
        return $this->readings;
    }

    public function addReading(Reading $reading): self
    {
        if (!$this->readings->contains($reading)) {
            $this->readings[] = $reading;
            $reading->setBook($this);
        }

        return $this;
    }

    public function removeReading(Reading $reading): self
    {
        if ($this->readings->removeElement($reading)) {
            // set the owning side to null (unless already changed)
            if ($reading->getBook() === $this) {
                $reading->setBook(null);
            }
        }

        return $this;
    }
}
