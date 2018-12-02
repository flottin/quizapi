<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\RequestStack;

class MailController extends AbstractController
{
private $request;
    public function __construct(RequestStack $request){
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
     * @Route("/mailing/add/", methods={"POST"})
     */
    public function add()
    {
        $mail = $this->request->getCurrentRequest()->get('mail');
        $id = $this->request->getCurrentRequest()->get('id');
        $client = $this->request->getCurrentRequest()->get('client');
        $res = [$id, $mail, $client];
        return $this->json($res);

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
