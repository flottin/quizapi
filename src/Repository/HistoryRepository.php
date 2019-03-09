<?php

namespace App\Repository;

use App\Entity\Client;
use App\Entity\History;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;


class HistoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, History::class);
    }

    public function findAll()
    {

        $queryBuilder = $this->createQueryBuilder('c')
            ->select('c')
            ->orderBy('c.id');

        $max = 30;
        //$max = -1;
        $limit = 100;
        $offset = 0;
        $count = 0;
        while (true) {
            $queryBuilder->setFirstResult($offset);
            $queryBuilder->setMaxResults($limit);
            $entities = $queryBuilder->getQuery()->getResult ();
            if (count($entities) === 0) {
                break;
            }
            foreach ($entities as $entity) {
                if ($count >= $max && $max !== -1) break 2;
                yield $entity;
                $this->_em->detach($entity);
                $count ++;
            }
            $offset += $limit;
        }
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
