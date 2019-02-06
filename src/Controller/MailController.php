<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Mailing;
use App\Entity\Type;
use App\Service\Common;
use App\Service\FtpService;
use App\Service\History;
use App\Service\PaginateService;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class MailController
 * @package App\Controller
 */
class MailController extends AbstractController
{
    /**
     * @var RequestStack
     */
    private $request;
    /**
     * @var History
     */
    private $historyService;
    /**
     * @var \App\Service\Mailing
     */
    private $mailingService;
    /**
     * @var PaginateService
     */
    private $paginateService;
    private $tpService;

    /**
     * MailController constructor.
     * @param RequestStack $request
     * @param \Swift_Mailer $mailer
     * @param History $historyService
     * @param ObjectManager $em
     * @param \App\Service\Mailing $mailingService
     * @param PaginateService $paginateService
     */
    public function __construct(
        RequestStack $request,
        \Swift_Mailer $mailer,
        History $historyService,
        ObjectManager $em,
    \App\Service\Mailing $mailingService,
    PaginateService $paginateService,
    FtpService $ftpService
    ){

        $this->em = $em;
        $this->historyService = $historyService;
        $this->mailingService = $mailingService;
        $this->paginateService = $paginateService;
        $this->ftpService = $ftpService;

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
     * @Route("/form/{id}")

     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function form($id, Request $request)
    {
$history = new \App\Entity\History();



dump($request);



        $form = $this->createFormBuilder($history)

            ->add('client', TextType::class)
            ->add('dateTime', DateType::class)
            ->add('mailMessage')
            ->add('pdfPath')
            ->add('submit', SubmitType::class, array('label' => 'Create Task'))
            ->getForm();

        return $this->render('form.html.twig', array(
            'form' => $form->createView(),
        ));

    }



    /**
     * @Route("/mail/{clientName}")
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
     * @Route("/file/{name}/{id}", requirements={"page"="\d+"})
     * @ParamConverter("client", options={"mapping": {"name": "name"}})
     * @param Client $client
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|void
     */
    public function fileAction(Client $client, int $id)
    {

        $dir = 'rapports_mpe';

        $file = '/Users/flottin/PhpstormProjects/quizapi/pdf/Rapport_production_PLACE-ORME-20190204-1641.pdf';
        $remote_file = "/home/flottin/".$dir . '/Rapport_production_PLACE-ORME-20190204-1641.pdf';

        $this->ftpService->connect ($client);
        $this->ftpService->put($remote_file, $file);

        $tmppdf = '/Users/flottin/PhpstormProjects/quizapi/var/pdf/';
        $file = $tmppdf . 'Rapport_production_PLACE-ORME-20190204-1641.pdf';

        $pdfPath = $this->ftpService->get($remote_file, $file);
        if(empty($pdfPath)){
            die('no file!');
        }
        return $this->file($pdfPath);

die;


$history = $this->em->getRepository (\App\Entity\History::class)->find ($id);

dump($history); die;

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
