<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Entity\Favorite;
use App\Entity\Reading;
use App\Repository\BookRepository;
use App\Repository\UserRepository;
use App\Repository\ReadingRepository;
use App\Repository\FavoriteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{

    /**
     * 
     * Get users collection
     * 
     * @Route("/api/users", name="api_users_get", methods={"GET"})
     */
    public function getCollection(UserRepository $userRepository): Response
    {
     
        // We get the data
        $userList = $userRepository->findAll();

        return $this->json(
            // Data to serialize (converted in JSON)
            $userList,
            // Status code
            Response::HTTP_OK,
            // Headers to add (none)
            [],
            // Groups to use by Serializer
            ['groups' => 'get_collection']
        );
    }

    /**
     * Get one user from Users data
     * 
     * @Route("/api/user/{id}", name="api_user_get", methods={"GET"})
     */
    public function getItem(UserRepository $userRepository, $id)
    {
        // We get the data
        $user = $userRepository->find($id);

        return $this->json(
            // User to Serialize
            $user,
            // Status Code
            Response::HTTP_OK,
            // Headers to add (none)
            [],
            // Groups
            ['groups' => 'get_item']);
    }

    /**
     * User registration
     * 
     * @Route("/api/registration", name="api_user_new", methods={"POST"})
     */
    public function new(Request $request, SerializerInterface $serializer, ManagerRegistry $doctrine, ValidatorInterface $validator, UserPasswordHasherInterface $userPasswordHasher)
    {
        // We get Json content from the request
        $jsonContent = $request->getContent();

        try {
            // We convert Json to Doctrine object
            $user = $serializer->deserialize($jsonContent, User::class, 'json');

        } catch (NotEncodableValueException $e) {
            // If the JSON is incorrect or missing, we send an error message
            return $this->json(
                ['error' => 'JSON invalide'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        // Entity validator
        $errors = $validator->validate($user);

        // Errors ?
        if (count($errors) > 0) {
            
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // password hasher component
        $hashedPassword = $userPasswordHasher->hashPassword($user, $user->getPassword());
        
        // Deleting the clear password with hashed one
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_USER']);
        $user->setCreatedAt(new \Datetime('now'));

        // We save the entity
        $entityManager = $doctrine->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        // We return the correct response (201 + Location: URL source)
        return $this->json(
            // User to Serialize
            $user,
            // Status Code
            Response::HTTP_CREATED,
            // Headers to add  (none)
            [],
            // Groups
            ['groups' => 'get_item']
        );
    }

    /**
     * Get own profile data
     * 
     * @Route("/api/profile", name="api_profile_get", methods={"GET"})
     */
    public function getProfile(UserRepository $userRepository)
    {
        // We get the user from the current JWT token
        $user = $this->getUser();
        
        // Otherwise authorization
        return $this->json(
            // User to Serialize
            $user,
            // Status Code
            Response::HTTP_OK,
            // Headers to add (none)
            [],
            // Groups
            ['groups' => 'get_item']);
    }

    /**
     * Update own profile
     * 
     * @Route("/api/profile", name="api_update_profile", methods={"PUT"})
     */
    public function updateProfile(Request $request, ManagerRegistry $doctrine, ValidatorInterface $validator, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher)
    {
       // We get the user from the current JWT token
        $user = $this->getUser();

        // We convert Json to Doctrine object
        $jsonContent = json_decode($request->getContent(), true);

        // password hasher component to hash the plain password
        // $hashedPassword = $userPasswordHasher->hashPassword($user, $jsonContent['password']);
        
        try {

            $user->setUsername($jsonContent['username']);
            $user->setEmail($jsonContent['email']);
            // $user->setPassword($hashedPassword);
            $user->setPresentation($jsonContent['presentation']);
            $user->setPicture($jsonContent['picture']);
            $user->setUpdatedAt(new \DateTime('now'));

        } catch (NotEncodableValueException $e) {
            // If the JSON is incorrect or missing, we send an error message
            return $this->json([
                'error' => 'La modification n\'a pas pu être effectuée'], Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        // Entity validator
        $errors = $validator->validate($user);

        // Errors ?
        if (count($errors) > 0) {
            
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // We save the entity
        $entityManager = $doctrine->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        // We return the correct response (201 + Location: URL source)
        return $this->json(
            // User to Serialize
            $user,
            // Status Code
            Response::HTTP_OK,
            // Headers to add (none)
            [],
            // Groups
            ['groups' => 'get_item_profile']
        );
    }

    /**
     * Delete own profile
     * 
     * @Route("/api/profile", name="api_delete_profile", methods={"DELETE"})
     */
    public function delete(EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {

        // We get the user from the current JWT token
        $user = $this->getUser();

        $entityManager->remove($user);
        $entityManager->flush();

        // We return the correct response 303
        return $this->json(
            // User to Serialize
            $user,
            // HTTP CODE 303 for a redirection
            Response::HTTP_SEE_OTHER,
            // Headers to add (none)
            [],
            // Groups
            ['groups' => 'get_item_profile']
        );
    }
    
    /**
     * Get own profile's favorites
     * 
     * @Route("/api/profile/favorites", name="api_profile_get_favorites", methods={"GET"})
     */
    public function getFavorites(UserRepository $userRepository)
    {
        // We get the user from the current JWT token
        $user = $this->getUser();
        
        // Get favorites
        $userFavorites = $user->getFavorites();

        // Otherwise authorization
        return $this->json(
            // Favorites to Serialize
            $userFavorites, 
            // Status Code
            Response::HTTP_OK, 
            //Headers to add (none)
            [], 
            // Groups
            ['groups' => 'get_item_favorite']);
    }

   /**
     * Adding new favorite
     * 
     * @Route("/api/profile/{id<\d+>}/addfavorite", name="api_favorite_new", methods={"POST"})
     * 
     */
    public function newFavorite(Request $request, SerializerInterface $serializer, ManagerRegistry $doctrine, ValidatorInterface $validator, BookRepository $bookRepository, UserRepository $userRepository, FavoriteRepository $favoriteRepository)
    {
        // We get the user from the current JWT token
        $user = $this->getUser();

        // We get Json content from the request
        $jsonContent = json_decode($request->getContent(), true);

        $userFavorite = $serializer->deserialize($request->getContent(), Favorite::class, 'json');
        
        $book = $bookRepository->find($jsonContent["book_isbn"]);

        // We search a match between the $book and the current $user
        $userFavoriteData = $favoriteRepository->findOneBy([
            'book' => $book,
            'user' => $user
        ]); 
        
        // If the user's favorite matching data is not empty
        if (!empty($userFavoriteData)) {
            // We return 422 Code status and don't add the new favorite
            return $this->json(
                ['error' => 'Ce livre est déjà dans votre collection'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
            
        } else {
            // If the user's favorite matching data is empty
            // We convert Json to Doctrine object
            $userFavorite->setAddedAt(new \Datetime('now'));
            $userFavorite->setUser($user);
            $userFavorite->setBook($book);
        }

        // Entity validator
        $errors = $validator->validate($userFavorite);

        // Errors ?
        if (count($errors) > 0) {
            // If there's one error or more we return 422 http code
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // We save the entity
        $entityManager = $doctrine->getManager();
        $entityManager->persist($userFavorite);
        $entityManager->flush();

        // We return the correct response (201 + Location: URL source)
        return $this->json(
            // Favorite to Serialize
            $userFavorite,
            // Status Code
            Response::HTTP_CREATED,
            // Headers to add
            ['Location' => $this->generateUrl('api_favorite_new', ['id' => $userFavorite->getId()])],
            // Groups
            ['groups' => 'get_item_favorite']
        );
    }


    /**
     * Delete own favorite
     * 
     * @Route("/api/profile/favorite/{id}", name="api_delete_profile_favorite", methods={"DELETE"})
     */
    public function deleteFavorite(EntityManagerInterface $entityManager, FavoriteRepository $favoriteRepository, $id): Response
    {

        // We get the user from the current JWT token
        $user = $this->getUser();

        // We search in favorite repository for the userID corresponding to the ID of the current favorite
        $userFavorites = $favoriteRepository->findOneBy([
            'id' => $id,
            'user' => $user
        ]);

        $entityManager->remove($userFavorites);
        $entityManager->flush();

        // We return the correct response 303
        return $this->json(
            // Favorites to Serialize
            $userFavorites,
            // HTTP CODE 303 for a redirection
            Response::HTTP_SEE_OTHER,
            [],
            // Groups
            ['groups' => 'get_item_favorite']
        );
    }
    

    /**
     * Adding new reading
     * 
     * @Route("/api/profile/{id<\d+>}/addreading", name="api_reading_new", methods={"POST"})
     */
    public function newReading(Request $request, SerializerInterface $serializer, ManagerRegistry $doctrine, ValidatorInterface $validator, BookRepository $bookRepository, ReadingRepository $readingRepository)
    {
        // We get the user from the current JWT token
        $user = $this->getUser();
        
        $jsonContent = json_decode($request->getContent(), true);

        // We get Json content from the request
        $userReading = $serializer->deserialize($request->getContent(), Reading::class, 'json');
        
        $book = $bookRepository->find($jsonContent["book_isbn"]);

        // We search a match between the $book and the current $user
        $userReadingData = $readingRepository->findOneBy([
            'book' => $book,
            'user' => $user
        ]); 
        
        // If the user's reading matching data is not empty
        if (!empty($userReadingData)) {
            // We return 422 Code status and don't add the new favorite
            return $this->json(
                ['error' => 'Ce livre est déjà dans votre collection'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
            
        } else {
            // If the user's reading matching data is empty
            // We convert Json to Doctrine object
            $userReading->setAddedAt(new \Datetime('now'));
            $userReading->setUser($user);
            $userReading->setBook($book);
        }


        // Entity validator
        $errors = $validator->validate($userReading);

        // Errors ?
        if (count($errors) > 0) {
            
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // We save the entity
        $entityManager = $doctrine->getManager();
        $entityManager->persist($userReading);
        $entityManager->flush();

        // We return the correct response (201 + Location: URL source)
        return $this->json(
            // Readings to Serialize
            $userReading,
            // Status Code
            Response::HTTP_CREATED,
            // Headers to add
            ['Location' => $this->generateUrl('api_reading_new', ['id' => $userReading->getId()])],
            // Groups
            ['groups' => 'get_item_reading']
        );
    }


    /**
     * Get own profile's readings
     * 
     * @Route("/api/profile/readings", name="api_profile_get_readings", methods={"GET"})
     */
    public function getReadings(UserRepository $userRepository)
    {
        // We get the user from the current JWT token
        $user = $this->getUser();
        
        $userReadings = $user->getReadings();

        // Otherwise authorization
        return $this->json(
            // Readings to Serialize
            $userReadings, 
            // Status Code
            Response::HTTP_OK, 
            // Headers to add (none)
            [], 
            // Groups
            ['groups' => 'get_item_reading']);
    }


    /**
     * Delete own reading
     * 
     * @Route("/api/profile/reading/{id}", name="api_delete_profile_reading", methods={"DELETE"})
     */
    public function deleteReading(EntityManagerInterface $entityManager, ReadingRepository $readingRepository, $id): Response
    {

        // We get the user from the current JWT token
        $user = $this->getUser();

        // We search in reading repository for the userID corresponding to the current reading ID
        $userReadings = $readingRepository->findOneBy([
            'id' => $id,
            'user' => $user
        ]);

        $entityManager->remove($userReadings);
        $entityManager->flush();

        // We return the correct response 303
        return $this->json(
            $userReadings,
            // HTTP CODE 303 for a redirection
            Response::HTTP_SEE_OTHER,
            [],
            // Groups
            ['groups' => 'get_item_reading']
        );
    }

}
