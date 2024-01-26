<?php

namespace App\symfony;

use App\symfony\Models\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use JetBrains\PhpStorm\NoReturn;

class DoctrineModel
{
    /**
     * @throws ORMException
     */
    public static function insert(EntityManager $em): void {
        // Create a new user
        $user = new User(...User::fakeWithId());

        $em->persist($user);
        $em->flush();
    }

    /**
     * @throws ORMException
     */
    public static function select(EntityManager $em): void {
        // Get the repository for the User entity
        $userRepository = $em->getRepository(User::class);
        /** @var User $user */
        $userRepository->findOneBy(['isActive' => true]);
    }
}