<?php

namespace App\DataFixtures;

use Cooolinho\Bundle\SecurityBundle\Entity\User;
use Cooolinho\Bundle\SecurityBundle\Entity\UserInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    protected const DEMO_PASSWORD = 'secret';

    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $this->addDemoUserData($user, 'admin', User::ROLE_ADMIN);

        $manager->persist($user);
        $manager->flush();
    }

    protected function addDemoUserData(
        UserInterface $user,
        string $username,
        string $role = User::ROLE_USER
    ): UserInterface
    {
        $user->setEmail(strtolower($username) . '@example.com');
        $user->setPassword($this->passwordEncoder->encodePassword($user, self::DEMO_PASSWORD));
        $user->addRole($role);

        return $user;
    }
}
