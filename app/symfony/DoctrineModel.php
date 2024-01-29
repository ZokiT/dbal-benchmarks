<?php

namespace App\symfony;

use App\Benchmark\Params;
use App\symfony\Models\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Exception;

class DoctrineModel
{
    /**
     * @throws ORMException
     * @throws Exception
     */
    public static function insert(Params $params): void {
        // Create a new user
        $user = new User(...User::fakeWithId());
        $em = $params->getParam('doctrineEntityManager');
        $em->persist($user);
        $em->flush();
    }

    /**
     * @throws ORMException
     */
    public static function select(Params $params): void {
        // Get the repository for the User entity
        $userRepository = $params->getParam('doctrineEntityManager')->getRepository(User::class);
        $limit = $params->getParam('selectLimit');
        /** @var User $user */
        $userRepository->findBy(['isActive' => true], null, $limit);
    }


    /**
     * @throws ORMException
     */
    public static function update(Params $params): void {
        /** @var EntityManager $em */
        $em = $params->getParam('doctrineEntityManager');
        /** @var User $user */
        $user = $params->getParam('user');

        $user->setEmail(uniqid() . 'ormupdate@example.com');

        $em->flush($user);
    }
}