<?php

namespace App\Controller\Api;

use App\Entity\Author;
use App\Repository\BookRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookController extends AbstractController
{

    /**
     * 
     * Get books collection
     * 
     * @Route("/api/books", name="api_books_get", methods={"GET"})
     */
    public function getCollection(BookRepository $bookRepository): Response
    {
     
        // We get all data
        $bookList = $bookRepository->findAll();

        return $this->json(
            // Data to serialize (converted in JSON)
            $bookList,
            // Status code
            Response::HTTP_OK,
            // Headers to add (none)
            [],
            // Groups to use by Serializer
            ['groups' => 'get_collection']
        );
    }


    /**
     * Get one item from Book data
     * 
     * @Route("/api/book/{id}", name="api_book_get", methods={"GET"})
     */
    public function getItem(BookRepository $bookRepository, $id)
    {

        // We get the data
        $book = $bookRepository->find($id);

        return $this->json(
            // Book to Serialize
            $book,
            // Status Code
            Response::HTTP_OK,
            // Headers to add (none)
            [],
            // Groups
            ['groups' => 'get_item']);
    }
}
