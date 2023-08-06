<?php

namespace App\DataFixtures;

use App\DTO\User\CreateUserRequest;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends BaseFixture
{
    public function __construct()
    {
    }

    protected function loadData(ObjectManager $manager): void
    {
        $this->createMany(User::class, 30, function ($count) {
            if($count == 0) {
                $dto = new CreateUserRequest();
                $dto->plainTextPassword = '';
                $dto->firstName = 'Strikan';
                $dto->lastName = 'Strikic';
                $dto->email = 'strikan@mail.com';
                $dto->username = 'strikan.strikic';
                return User::create($dto, preHashedPassword: '$2y$13$lfmF3W9/G3cwhF0YyKuB7OON00NwJScuT7285utvvvsv4KKdr644.');
            } else {
                $dto = new CreateUserRequest();
                $dto->plainTextPassword = '';
                $dto->firstName = $this->faker->firstName;
                $dto->lastName = $this->faker->lastName;
                $dto->email = $this->faker->email;
                $dto->username = $dto->firstName . '.' . $dto->lastName;
                return User::create($dto, preHashedPassword: '$2y$13$lfmF3W9/G3cwhF0YyKuB7OON00NwJScuT7285utvvvsv4KKdr644.');
            }
        });

        $manager->flush();
    }
}
