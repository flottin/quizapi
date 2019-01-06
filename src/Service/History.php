<?php
namespace App\Service;

use App\Entity\Client;
use App\Entity\Type;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Question;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class History
{
    protected $em;


    public function __construct ( ObjectManager $em  = null, RequestStack $request)
    {
        $this->em = $em;
    }


    public function getHistory ($client, int $page, $max = 20)
    {
        $start = $page * $max - $max;
        $res = $this->em
            ->getRepository (\App\Entity\History::class)
            ->search($client, null, $start, $max);

        $result = [];
        for ($i = 0 ; $i < $max ; $i++){
            $result [] = isset ($res[$i]) ? $res[$i] : null;
        }

        return $result;

    }


    public function getHistoryByDate ($client, $date, $max = 20)
    {
        $res =  $this->em
            ->getRepository (\App\Entity\History::class)
            ->search($client, $date);

        $result = [];
        for ($i = 0 ; $i < $max ; $i++){
            $result [] = isset ($res[$i]) ? $res[$i] : null;
        }
        return $result;

    }

    public function setHistory(Client $client, String $pdfPath, String $mail, Type $type)
    {
        $history = new \App\Entity\History();

        $history->setClient($client);
        $history->setDateTime ();
        $history->setPdfPath ($pdfPath);
        $history->setMailMessage($mail);
        $history->setType($type);
        $this->em->persist($history);
        $this->em->flush();

    }

}