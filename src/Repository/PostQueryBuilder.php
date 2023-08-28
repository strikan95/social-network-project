<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

class PostQueryBuilder
{
    private QueryBuilder $qb;

    public function __construct(
        private readonly EntityManagerInterface $em
    )
    {
        $this->qb = $this->em->createQueryBuilder()
            ->select('p')
            ->from(Post::class, 'p');
    }

    public function latest(): self
    {
        $this->qb
            ->orderBy('p.createdAt', 'DESC');

        return $this;
    }


    public function following(int $userId): self
    {
        $this->qb
            ->join('p.user', 'u')
            ->join('u.followers', 'f')
            ->where('f.id = :currentUserId')
            ->setParameter('currentUserId', $userId);
/*            ->join('u.following', 'f')
            ->where('f.id = :currentUserId')
            ->setParameter('currentUserId', $userId);*/

        return $this;
    }

    public function public(): self
    {
        $this->qb
            ->where('p.access = :access')
            ->setParameter('access', 'Public');
        return $this;
    }

    public function getQueryBuilder(): QueryBuilder
    {
        return $this->qb;
    }

    public function getQuery(): Query
    {
        return $this->qb->getQuery();
    }

    public function executeQuery()
    {
        return $this->qb->getQuery()->execute();
    }
}