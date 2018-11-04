<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\Questions;

class QuizController extends AbstractController
{


    public function __construct(){

    }
    /**
     * @Route("/quiz/{limit}", name="quiz")
     */
    public function index($limit = null)
    {
        $em = $this->getDoctrine()->getManager();
        $questionsService = new Questions($em);
        $questions = $questionsService->getQuestions ($limit);
        return $this->json($questions);
    }
}
