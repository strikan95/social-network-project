<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Form\CreatePostForm;
use App\Repository\Interfaces\PostRepositoryInterface;
use App\Repository\Interfaces\UserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    public function __construct(
        private readonly PostRepositoryInterface $postRepository
    )
    {
    }

    #[Route('/post', name: 'app.post.create')]
    public function createPost(Request $request): Response
    {
        $form = $this->createForm(CreatePostForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $author */
            $author = $this->getUser();

            // Create and publish
            $post = Post::create($author, $form->getData());
            $this->postRepository->save($post, true);
        }

        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
            'form' => $form
        ]);
    }
}
