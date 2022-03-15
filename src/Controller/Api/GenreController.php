<?php

namespace App\Controller\Api;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GenreController extends AbstractController
{
    /**
     * Get genres collection
     * 
     * @Route("/api/genres", name="api_genres_get", methods={"GET"})
     */
    public function getCollection(GenreRepository $genreRepository): Response
    {
        // We get all data
        $genresList = $genreRepository->findAll();

        return $this->json(
            // Genres to Serialize
            $genresList,
            // Status Code
            Response::HTTP_OK,
            // Headers to add (none)
            [],
            // Groups
            ['groups' => 'get_collection']);
    }

    /**
     * Get books by genre ID
     * 
     * @Route("/api/books/{id<\d+>}/genres", name="api_books_get_genres", methods={"GET"})
     */
    public function getItemAndBooks(Genre $genre): Response
    {
        // We get the data
        $bookList = $genre->getBooks();
        
        $data = [
            'genre' => $genre,
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
