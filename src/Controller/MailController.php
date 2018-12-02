<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MailController extends AbstractController
{

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


}
