<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Mailing;
use App\Entity\Type;
use App\Service\Common;
use App\Service\History;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\RequestStack;

class AjaxController extends AbstractController
{
    private $request;

    private $historyService;

    private $mailingService;

    public function __construct(
        RequestStack $request,
        \Swift_Mailer $mailer,
        History $historyService,
        ObjectManager $em,
        \App\Service\Mailing $mailingService
    ){

        $this->em = $em;
        $this->historyService = $historyService;
        $this->mailingService = $mailingService;

        //Common::setClient ($em, $request);

        $this->request = $request;
    }
    /**
     * @Route("/historydate/{clientName}/{month}/{day}/{year}")
     */
    public function index($clientName = 'place', $month, $day, $year)
    {
        $client = $this->em->getRepository (Client::class)
            ->findOneBy(['name' => $clientName]);

        $date = "$year-$month-$day";

        $objDateTime = new \DateTime($date);




        $res = $this->historyService->getHistoryByDate ($client, $objDateTime);
        return $this->json($res);


    }

    /**
     * @Route("/history/{clientName}/{page}")
     */
    public function historyPage($clientName = 'place', $page)
    {
        $client = $this->em->getRepository (Client::class)
            ->findOneBy(['name' => $clientName]);

        $res = $this->historyService->getHistory ($client, $page);
        return $this->json($res);


    }
}
