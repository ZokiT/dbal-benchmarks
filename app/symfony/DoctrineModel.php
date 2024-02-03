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
    public static function insert(Params $params): Params {
        $user = new User(...User::fakeWithId());
        $em = $params->getParam('doctrineEntityManager');
        $em->persist($user);
        $em->flush();

        return $params;
    }

    /**
     * @throws ORMException
     */
    public static function select(Params $params): Params {
        // Get the repository for the User entity
        $userRepository = $params->getParam('doctrineEntityManager')->getRepository(User::class);
        $limit = $params->getParam('selectLimit');
        /** @var User $user */
        $userRepository->findBy(['isActive' => true], null, $limit);

        return $params;
    }


    /**
     * @throws ORMException
     */
    public static function update(Params $params): Params {
        /** @var EntityManager $em */
        $em = $params->getParam('doctrineEntityManager');
        /** @var User $user */
        $user = $params->getParam('user');

        $user->setEmail(uniqid() . 'ormupdate@example.com');

        $em->flush($user);

        return $params;
    }

    /**
     * @throws ORMException
     */
    public static function delete(Params $params): Params {
        /** @var EntityManager $em */
        $em = $params->getParam('doctrineEntityManager');
        $userRepository = $em->getRepository(User::class);

        $user = $userRepository->find($params->getParam('minUserId'));
        $em->remove($user);
        $em->flush();

        $params->addParam('minUserId', $params->getParam('minUserId') + 1);

        return $params;
    }
}