<?php

namespace App\symfony;

use App\symfony\Models\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;

class DoctrineModelInsert
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
}