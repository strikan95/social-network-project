<?php

namespace App\Controller;

use App\Form\PostType;
use App\Entity\Post;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post')]
    public function __invoke(Request $request, EntityManagerInterface $entityManager): Response
    {
        $post = new Post();

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() ){
            $post = $form->getData();
            $entityManager->persist($post);
            $entityManager->flush();
            return new Response('Saved new product with id '.$post->getId());
        }

        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
            'form' => $form
        ]);
    }
}
