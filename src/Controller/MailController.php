<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Mailing;
use App\Entity\Type;
use App\Service\History;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\RequestStack;

class MailController extends AbstractController
{
private $request;
    public function __construct(RequestStack $request, \Swift_Mailer $mailer, History $historyService, ObjectManager $em){

        $this->em = $em;

        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('flottin@gmail.com')
            ->setTo('flottin@gmail.com')
            ->setBody(
                'okko',
                'text/html'
            )
        ;

        $mailer->send($message);

        $this->request = $request;
    }
    /**
     * @Route("/mail/{client}")
     */
    public function index($client = null)
    {
        //$questionsService = $this->container->get('Questions');
        //$questions = $questionsService->getQuestions ($limit);
        //return $this->json($questions);
        return $this->render('mail/index.html.twig', array(
            'articles' => [],
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
