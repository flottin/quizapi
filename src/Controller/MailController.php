<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Mailing;
use App\Entity\Type;
use App\Service\Common;
use App\Service\History;
use App\Service\PaginateService;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\RequestStack;

class MailController extends AbstractController
{
private $request;
private $historyService;
private $mailingService;
private $paginateService;

    public function __construct(
        RequestStack $request,
        \Swift_Mailer $mailer,
        History $historyService,
        ObjectManager $em,
    \App\Service\Mailing $mailingService,
    PaginateService $paginateService
    ){

        $this->em = $em;
        $this->historyService = $historyService;
        $this->mailingService = $mailingService;
        $this->paginateService = $paginateService;

        Common::setClient ($em, $request);


//        $message = (new \Swift_Message('Hello Email'))
//            ->setFrom('flottin@gmail.com')
//            ->setTo('flottin@gmail.com')
//            ->setBody(
//                'okko',
//                'text/html'
//            )
//        ;
//
//        $mailer->send($message);

        $this->request = $request;
    }
    /**
     * @Route("/{clientName}")
     */
    public function index($clientName = 'place')
    {
        $client = $this->em->getRepository (Client::class)->findOneBy(['name' => $clientName]);
        $history = $this->historyService->getHistoryByDate  ($client, null);
        $countHistory = $this->em->getRepository (\App\Entity\History::class)->num($client);
        $pages = $this->paginateService->page($countHistory);

        return $this->render('mail/index.html.twig', array(
            'history' => $history,
            'countHistory' => $countHistory,
            'pages' => $pages,

        ));

    }

    /**
     * @Route("/mail/{clientName}")
     */
    public function index2($clientName = null)
    {
        print_r(Common::getClient ());


print_r  ($this->historyService->getHistory ());
        die;


        $client = $this->em->getRepository (Client::class)->findOneBy(['name' => $clientName]);

        $history = $this->em->getRepository (\App\Entity\History::class)->findBy(['client' => $client]);
        $mailings = $this->em->getRepository (Mailing::class)->findBy(['client' => $client]);

        $listAuto = [];
        $listManuel = [];
        foreach($mailings as $mail){
            if($mail->getType()->getType() === 'auto'){
                $listAuto [] = $mail->getMail();

            } else {
                $listManuel [] = $mail->getMail();

            }
        }

        $auto = implode(',', $listAuto);
        $manuel = implode(',', $listManuel);
        return $this->render('mail/index.html.twig', array(
            'history' => $history,
            'listManuel' => $manuel,
            'listAuto' => $auto,
        ));
    }


    /**
     * @Route("/mailing/add/{clientName}", methods={"POST"})
     */
    public function add($clientName)
    {
        $mailing = new \App\Service\Mailing($this->em);
        $client = $this->em->getRepository (Client::class)->findOneBy(['name' => $clientName]);
        $datas = json_decode(file_get_contents('php://input'), true);
        $mailing->save ($datas, $client);
        return $this->json(true);

    }

    /**
     * @Route("/mailing/delete/", methods={"POST"})
     */
    public function delete()
    {
        $id = $this->request->getCurrentRequest()->get('id');
        $client = $this->request->getCurrentRequest()->get('client');
        $res = [$id, $client];
        return $this->json($res);

    }

    /**
     * @Route("/mailing/list/{client}/{type}", methods={"GET"})
     */
    public function list($client, $type)
    {
        $id = "id";

        $res = [
            ['id' => 'O', 'title' => 'mail'],
            ['id' => '1', 'title' => 'mail1'],
        ];

        if ('auto' === $type){
            $res = [
                ['id' => 'O', 'title' => 'mail auto'],
                ['id' => '1', 'title' => 'mail1 auto'],
                ['id' => '2', 'title' => 'mail2 auto'],
            ];
        }

        return $this->json($res);

    }


}
