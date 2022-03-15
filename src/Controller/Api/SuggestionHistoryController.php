<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Response;
use App\Repository\SuggestionHistoryRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SuggestionHistoryController extends AbstractController
{
    /**
     * @Route("/api/suggestions", name="api_get_suggestion", methods={"GET"})
     */
    public function getCollection(SuggestionHistoryRepository $suggestionHistoryRepository): Response
    {
       // We get all data
       $suggestionList = $suggestionHistoryRepository->findAll();

       return $this->json(
           // Data to serialize (converted in JSON)
           $suggestionList,
           // Status code
           Response::HTTP_OK,
           // Headers to add (none)
           [],
           // Groups to use by Serializer
           ['groups' => 'get_collection_suggestion']
       );
    }
}
