<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\Interfaces\PostRepositoryInterface;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Services\PostService;
use App\Form\CreatePostForm;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FeedController extends AbstractController
{
    public function __construct(
        private readonly PostRepositoryInterface $postRepository
    )
    {
    }

    #[Route('/feed', name: 'app.feed.show')]
    public function index(Request $request): Response
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

        return $this->render('pages/feed_page.html.twig', [
            'posts' => $this->postRepository->findAll(),
            'form' => $form,
            'user' => $this->getUser()
        ]);
    }
}
