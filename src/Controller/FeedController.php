<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Post;
use App\Form\PostType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FeedController extends AbstractController
{
    #[Route('/feed', name: 'app_feed')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $publicPosts = $entityManager->getRepository(Post::class)->findBy(['access' => 'Public'], ['id' => 'DESC']);
        
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() ){
            $post = $form->getData();
            $entityManager->persist($post);
            $entityManager->flush();
            return $this->redirect($request->getUri());
        }

        return $this->render('pages/feed_page.html.twig', [
            'posts' => $publicPosts,
            'form' => $form,
            'time' => time()
        ]);
    }
}
