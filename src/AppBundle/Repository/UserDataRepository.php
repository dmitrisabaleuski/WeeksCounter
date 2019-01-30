<?php

namespace AppBundle\Repository;

use AppBundle\Entity\UserData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\EntityRepository;

/**
 * @method UserData|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserData|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserData[]    findAll()
 * @method UserData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserDataRepository extends EntityRepository
{

    /**
    * @return UserData[] Returns an array of UserData objects
    */

    public function findAllOrderedById($value)
    {
        return $this->getEntityManager()
                    ->createQuery(
                        'SELECT p FROM AppBundle:UserData p ORDER BY p.taxonomy_user_id ASC'
                    )
                    ->getResult();
    }

}
