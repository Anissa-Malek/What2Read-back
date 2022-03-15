<?php

namespace App\Controller\Api;

use App\Entity\Book;
use App\Entity\User;
use App\Entity\Review;
use App\Repository\BookRepository;
use App\Repository\UserRepository;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

class ReviewController extends AbstractController
{
    /**
     * Get reviews collection
     * 
     * @Route("/api/reviews", name="api_reviews_get", methods={"GET"})
     */
    public function getCollection(ReviewRepository $reviewRepository): Response
    {
        // We get all data
        $reviewsList = $reviewRepository->findAll();

        return $this->json(
            // Data to serialize (converted in JSON)
            $reviewsList, 
            // Status code
            Response::HTTP_OK, 
            // Headers to add (none)
            [],
            // Groups to use by Serializer
            ['groups' => 'get_collection']);
    }

    /**
     * Get reviews by User ID
     * 
     * @Route("/api/user/{id<\d+>}/reviews", name="api_user_get_reviews", methods={"GET"})
     */
    public function getItemAndUsers(User $user): Response
    {
        // We get the data
        $reviewsList = $user->getReviews();
        
        $data = [
            'user' => $user,
            'review' => $reviewsList,
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

    /**
     * Get reviews by Book
     * 
     * @Route("/api/book/{id<\d+>}/reviews", name="api_get_book_reviews", methods={"GET"})
     */
    public function getReviewsByBook(Book $book): Response
    {
        // We get the data
        $reviewsList = $book->getReviews();

        $data = [
            'book' => $book,
            'reviews' => $reviewsList
        ];

        return $this->json(
            // Data to serialize (converted in JSON)
            $data,
            // Status code
            Response::HTTP_OK,
            // Headers to add (none)
            [],
            // Groups to use by Serializer
            ['groups' => 'get_item_book_reviews']
        );
    }

    /**
     * Get own user's reviews
     * 
     * @Route("/api/profile/reviews", name="api_get_profile_reviews", methods={"GET"})
     */
    public function show(ReviewRepository $reviewRepository)
    {
        // We get the user from the current JWT token
        $user = $this->getUser();

        // We get user's reviews 
        $review = $reviewRepository->findBy([
            'user' => $user
        ]);

        // We return the correct response
        return $this->json(
            $review,
            Response::HTTP_OK,
            [],
            // Groups
            ['groups' => 'get_collection', 'get_item']
        );
    }
   
    /**
     * Adding new review
     * 
     * @Route("/api/profile/{id<\d+>}/addreview", name="api_review_new", methods={"POST"})
     */
    public function newReview(Request $request, SerializerInterface $serializer, ManagerRegistry $doctrine, ValidatorInterface $validator, BookRepository $bookRepository)
    {
        // We get Json content from the request
        $review = $serializer->deserialize($request->getContent(), Review::class, 'json');

        $jsonContent = json_decode($request->getContent(), true);
        
        // We get the user from the current JWT token
        $user = $this->getUser();

        try {
            // We convert Json to Doctrine object
            $review->setCreatedAt(new \Datetime('now'));
            $review->setUser($user);
            $book = $bookRepository->find($jsonContent["book_isbn"]);
            $review->setBook($book);

        } catch (NotEncodableValueException $e) {
            // If the JSON is incorrect or missing, we send an error message
            return $this->json(
                ['error' => 'JSON invalide'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        // Entity validator
        $errors = $validator->validate($review);

        // Errors ?
        if (count($errors) > 0) {
            // If errors, we return a http code 422
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // We save the entity
        $entityManager = $doctrine->getManager();
        $entityManager->persist($review);
        $entityManager->flush();

        // We return the correct response (201 + Location: URL source)
        return $this->json(
            $review,
            Response::HTTP_CREATED,
            ['Location' => $this->generateUrl('api_review_new', ['id' => $review->getId()])],
            // Groups
            ['groups' => 'get_item']
        );
    }

    /**
     * Update own review
     * 
     * @Route("/api/profile/review/{id<\d+>}", name="api_update_profile_review", methods={"PUT"})
     */
    public function updateReview(Request $request, ManagerRegistry $doctrine, ValidatorInterface $validator, ReviewRepository $reviewRepository, $id)
    {
        // We get the user from the current JWT token
        $user = $this->getUser();

        // We search in reviewRepository for the userID corresponding to the ID of the current review
        $review = $reviewRepository->findOneBy([
            'id' => $id,
            'user' => $user
        ]);

        // We convert Json to Doctrine object
        $jsonContent = json_decode($request->getContent(), true);

        try {

            $review->setTitle($jsonContent['title']);
            $review->setContent($jsonContent['content']);
            $review->setUpdatedAt(new \Datetime('now'));

        } catch (NotEncodableValueException $e) {
            // If the JSON is incorrect or missing, we send an error message
            return $this->json(
                ['error' => 'JSON invalide'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        // Entity validator
        $errors = $validator->validate($review);

        // Errors ?
        if (count($errors) > 0) {
            
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // We save the entity
        $entityManager = $doctrine->getManager();
        $entityManager->persist($review);
        $entityManager->flush();

        // We return the correct response (201 + Location: URL source)
        return $this->json(
            $review,
            Response::HTTP_OK,
            [],
            // Groups
            ['groups' => 'get_item']
        );
    }

    /**
     * Delete own review
     * 
     * @Route("/api/profile/review/{id<\d+>}", name="api_delete_profile_review", methods={"DELETE"})
     */
    public function delete(EntityManagerInterface $entityManager, ReviewRepository $reviewRepository, $id): Response
    {

        // We get the user from the current JWT token
        $user = $this->getUser();

        // We search in reviewRepository for the userID corresponding to the ID of the current review
        $review = $reviewRepository->findOneBy([
            'id' => $id,
            'user' => $user
        ]);

        $entityManager->remove($review);
        $entityManager->flush();

        // We return the correct response 303
        return $this->json(
            $review,
            // HTTP CODE 303 for a redirection
            Response::HTTP_SEE_OTHER,
            [],
            // Groups
            ['groups' => 'get_item']
        );
    }
}
