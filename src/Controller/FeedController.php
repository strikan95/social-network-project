<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FeedController extends AbstractController
{
    #[Route('/feed', name: 'app_feed')]
    public function index(EntityManagerInterface $entityManager): Response
    {

        $posts = $entityManager->getRepository(Post::class)->findBy(['access' => 'Public']);

        return $this->render('pages/feed_page.html.twig', [
            'posts' => $posts,
            'time' => time()
        ]);
    }
}
