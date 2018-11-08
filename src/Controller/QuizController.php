<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends AbstractController
{

    /**
     * @Route("/quiz/{limit}", name="quiz")
     */
    public function index($limit = null)
    {
        $questionsService = $this->container->get('Questions');
        $questions = $questionsService->getQuestions ($limit);
        return $this->json($questions);
    }
}
