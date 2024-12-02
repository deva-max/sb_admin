<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Password\PasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    public function createUser(PasswordHasherInterface $hasher, EntityManagerInterface $em): Response
    {
        // Create a new User instance
        $user = new User();
        $user->setEmail('user@example.com');
        
        // Hash the password
        $hashedPassword = $hasher->hash('password123');
        $user->setPassword($hashedPassword);
        
        // Persist the user in the database
        $em->persist($user);
        $em->flush();

        // Return a response
        return new Response('User created successfully!');
    }
}
