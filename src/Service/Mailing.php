<?php
namespace App\Service;

use App\Entity\Client;
use App\Entity\Type;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Question;

class Mailing
{
    private $em;

    public function __construct ( ObjectManager $em  = null)
    {
        $this->em = $em;
    }

    /**
     * @param null $limit
     * @return array
     */
    public function getQuestions ($limit = null)
    {
        return $this->em
            ->getRepository ( Question::class )
            ->findBy ([], null, $limit);

    }

    public function setMailings(Array $mailings, Client $client, Type $type)
    {
        foreach ($mailings as $mailing) {
            self::setMailing ( $client, $mailing, $type );
        }
        $this->em->flush();
    }


    public function setMailing(Client $client, String $mail, Type $type, $flush = false)
    {
        $history = new \App\Entity\Mailing();
        $history->setClient($client);
        $history->setMail($mail);
        $history->setType($type);
        $this->em->persist($history);
        if (true === $flush){
            $this->em->flush();
        }
    }
}