<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PeopleController extends AbstractController
{
    #[Route('/people', name: 'app_people')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $userRepo = $entityManager->getRepository(User::class); 
        $limitUsers = $userRepo->createQueryBuilder('people')
        ->where("people.id != :user")
        ->setParameter("user", $user)
        ->setMaxResults(9)
        ->getQuery()
        ->getResult();

        return $this->render('pages/find_people_page.html.twig', [
            'users' => $limitUsers,
            'user' => $user
        ]);
    }
}
