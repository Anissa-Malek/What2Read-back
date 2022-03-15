<?php

namespace App\Controller\Api;

use App\Repository\AuthorRepository;
use App\Entity\Author;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    /**
     * Get authors collection
     * 
     * @Route("/api/authors", name="api_authors_get", methods={"GET"})
     */
    public function getCollection(AuthorRepository $authorRepository): Response
    {
        // We get all data
        $authorsList = $authorRepository->findAll();

        return $this->json(
            // Author to Serialize
            $authorsList,
            // Status Code
            Response::HTTP_OK,
            // Headers to add (none)
            [],
            // Groups
            ['groups' => 'get_collection']);
    }

    /**
     * Get books by author ID
     * 
     * @Route("/api/books/{id<\d+>}/authors", name="api_books_get_authors", methods={"GET"})
     */
    public function getItemAndBooks(Author $author): Response
    {
        // We get the data
        $bookList = $author->getBooks();
        
        $data = [
            'author' => $author,
            'book' => $bookList,
        ];

        return $this->json(
            // Data to serialize (converted in JSON)
            $data,
            // Status code
            Response::HTTP_OK,
            // Headers to add (none)
            [],
            // Groups to use by Serializer
            ['groups' => 'get_collection', 'get_item']
        );
    }
}
