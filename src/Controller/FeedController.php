<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\Interfaces\PostRepositoryInterface;
use App\Form\CreatePostForm;
use App\Repository\PostRepository;
use Knp\Component\Pager\PaginatorInterface;
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
    public function index(Request $request, PaginatorInterface $paginator): Response
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

        $pqb = $this->postRepository->withBuilder()
            ->latest()
            ->following($this->getUser()->id())
            ->getQueryBuilder();

        $pagination = $paginator->paginate(
            $pqb,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('pages/feed_page.html.twig', [
            'posts' => $pagination->getItems(),
            'form' => $form,
            'user' => $this->getUser()
        ]);
    }
}
