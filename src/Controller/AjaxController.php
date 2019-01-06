<?php

namespace App\Controller;

use App\Entity\Client;
use App\Service\History;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class AjaxController extends AbstractController
{

    private  $historyService;

    protected $container;
    protected $cache;

    public function __construct(
        History $historyService,
        ObjectManager $em

    ){
        $this->em = $em;
        $this->historyService = $historyService;

    }
    /**
     * @Route("/history/{name}/{page}", requirements={"page"="\d+"})
     * @ParamConverter("client", options={"mapping": {"name": "name"}})
     * @param Client $client
     * @param int $page
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function historyPage(Client $client, int $page)
    {
        $res = $this->historyService->getHistory ($client, $page);
        return $this->json($res);


    }

    /**
     * @param Client $client
     * @param \DateTime $date
     * @Route("/history/{name}/{date}")
     * @ParamConverter("date", options={"format": "Y-m-d"})
     * @ParamConverter("client", options={"mapping": {"name": "name"}})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function index(Client $client, \DateTime $date)
    {
        $res = $this->historyService->getHistoryByDate ($client, $date);
        return $this->json($res);


    }


}
