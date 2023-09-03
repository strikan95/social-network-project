<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\SearchUsersType;

class PeopleController extends AbstractController
{
    #[Route('/people', name: 'app_people')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $user = $this->getUser();
        $userRepo = $entityManager->getRepository(User::class); 
        $followingUsers = $user->following();
        $followingUserIds = [];
        foreach ($followingUsers as $followingUser) {
            $followingUserIds[] = $followingUser->id();
        }
        if(empty($followingUserIds)){
            $limitUsers = $userRepo->createQueryBuilder('people')
            ->where("people.id != :user")
            ->setParameter("user", $user)
            ->setMaxResults(9)
            ->getQuery()
            ->getResult();
        } else {
            $limitUsers = $userRepo->createQueryBuilder('people')
            ->where("people.id != :user")
            ->andWhere('people.id NOT IN (:following)')
            ->setParameter("user", $user)
            ->setParameter('following', $followingUserIds)
            ->setMaxResults(9)
            ->getQuery()
            ->getResult();
        }

        $searchForm = $this->createForm(SearchUsersType::class);
        $searchForm->handleRequest($request);
        $query = $searchForm["query"]->getData();
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            return $this->redirectToRoute('app.search', ['query' => $query]);
        }

        return $this->render('pages/find_people_page.html.twig', [
            'users' => $limitUsers,
            'user' => $user,
            'searchForm' => $searchForm,
        ]);
    }

    #[Route('/search/{query}', name: 'app.search')]
    public function search(Request $request,EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $userRepo = $entityManager->getRepository(User::class); 
        $query = $request->get('query');

        $matchingUsers = $userRepo->createQueryBuilder('people')
        ->where("people.id != :user")
        ->andWhere(
            "people.username LIKE :query OR people.firstName LIKE :query OR people.lastName LIKE :query"
        )
        ->setParameter("user", $user)
        ->setParameter("query", '%' . $query . '%')
        ->getQuery()
        ->getResult();  

        return $this->render('pages/search_people_page.html.twig', [
            'user' => $user,
            'query' => $query,
            'matchingUsers' => $matchingUsers
        ]);
    }
}
