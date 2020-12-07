<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        // Admin User
        $manager->persist($this->createUser('admin@admin.fr', 'PayGreenAdmin', ['ROLE_ADMIN']));

        // Lambda User
        $manager->persist($this->createUser('lambda@lambda.fr', 'PayGreenLambda', ['ROLE_USER']));

        // Fake Data for listing
        for ($i = 0; $i < 10; $i++) {
            $article = new Article();
            $article->setTitle($faker->text(120))
                ->setContent($faker->text(400));

            $manager->persist($article);
            $manager->persist($this->createUser($faker->email, 'HelloPayGreen'));
        }

        $manager->flush();
    }

    public function createUser($email, $password, $role = '')
    {
        $user = new User();
        $hash = $this->encoder->encodePassword($user, $password);

        $user->setEmail($email)
            ->setPassword($hash);


        if (is_array($role)) {
            $user->setRoles($role);
        }

        return $user;
    }
}
