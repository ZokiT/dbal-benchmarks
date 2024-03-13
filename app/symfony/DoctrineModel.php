<?php

namespace App\symfony;

use App\Benchmark\Params;
use App\symfony\Models\Order;
use App\symfony\Models\OrderDetails;
use App\symfony\Models\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\Query\Expr\Join;
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

    /**
     * @throws ORMException
     */
    public static function complexQuerySelect(Params $params): Params {
        $userRepository = $params->getParam('doctrineEntityManager')->getRepository(User::class, 'u');
        $limit = $params->getParam('selectLimit');

        $qb = $userRepository->createQueryBuilder('u')
                             ->select('u')
                             ->leftJoin('u.orders', 'o')
                             ->leftJoin('o.orderDetails', 'od')
                             ->where('u.isActive = :isActive')
                             ->andWhere('o.status IN (:status1, :status2)')
                             ->setParameter('status1', 'completed')
                             ->setParameter('status2', 'pending')
                             ->setParameter('isActive', true)
                             ->groupBy('u.username, u.id, o.orderDate, o.status')
                             ->having('SUM(od.quantity)  > :quantity')
                             ->setParameter('quantity', 5)
                             ->orderBy('u.id', 'ASC')
                             ->setMaxResults($limit);

        $users = $qb->getQuery()->getResult();

        return $params;
    }
}