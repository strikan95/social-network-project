<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\Interfaces\PostRepositoryInterface;
use App\Form\CreatePostForm;
use App\Form\SearchUsersType;
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

            $post = Post::create($author, $form->getData());
            $this->postRepository->save($post, true);
        }

        $searchForm = $this->createForm(SearchUsersType::class);
        $searchForm->handleRequest($request);
        $query = $searchForm["query"]->getData();
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            return $this->redirectToRoute('app.search', ['query' => $query]);
        }

        $pqb = $this->postRepository->withBuilder()
            ->latest()
            ->following($this->getUser()->id())
            ->getQueryBuilder();

        $pqbPublic = $this->postRepository->withBuilder()
                    ->latest()
                    ->public()
                    ->getQueryBuilder();

        $pagination = $paginator->paginate(
            $pqb,
            $request->query->getInt('page', 1),
            10
        );

        $paginationPublic = $paginator->paginate(
            $pqbPublic,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('pages/feed_page.html.twig', [
            'posts' => $pagination->getItems(),
            'publicPosts' => $paginationPublic->getItems(),
            'form' => $form,
            'searchForm' => $searchForm,
            'user' => $this->getUser(),
            'time' =>  time()
        ]);
    }
}
