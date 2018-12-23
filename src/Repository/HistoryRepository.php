<?php

namespace App\Repository;

use App\Entity\Client;
use App\Entity\History;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Question|null find($id, $lockMode = null, $lockVersion = null)
 * @method Question|null findOneBy(array $criteria, array $orderBy = null)
 * @method Question[]    findAll()
 * @method Question[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, History::class);
    }

    public function search(Client $client, \DateTime $date = null, $start = 0, $max = 20)
    {

        $query = $this->createQueryBuilder('q');

        if (!empty($date)){
            $dateMin = $date->format('Y-m-d 00:00:00');
            $dateMax = $date->format('Y-m-d 23:59:59');
            $query
                ->andWhere('q.dateTime between  :dateMin AND :dateMax')
                ->setParameter('dateMin', $dateMin)
                ->setParameter('dateMax', $dateMax);
        }

        $query
            ->setFirstResult($start);

        $query
            ->orderBy('q.id', 'DESC')
            ->setMaxResults($max);

        return $query ->getQuery()
            ->getResult()
            ;
    }

    public function num(Client $client)
    {

        $query = $this->createQueryBuilder('q');

        return $this->count(['client' => $client]);

    }


}
