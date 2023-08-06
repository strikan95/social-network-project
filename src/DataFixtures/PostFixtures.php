<?php

namespace App\DataFixtures;

use App\DTO\Post\CreatePostRequest;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PostFixtures extends BaseFixture implements  DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }

    protected function loadData(ObjectManager $manager): void
    {
        $this->createMany(Post::class, 100, function () {
            $dto = new CreatePostRequest();
            $dto->access = rand(0, 12) >=6 ? 'Public' : 'Private';
            $dto->content = $this->faker->realText(128);

            $author = $this->getRandomReference(User::class);

            return Post::create(
                $author,
                $dto
            );

        });

        $manager->flush();
    }
}
