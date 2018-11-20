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


    public function clean($str, $category)
    {
        $res        = trim(str_ireplace ($category, '', $str));
        $res        = str_ireplace ('-', '', $res);
        $results    = explode('_', $res);
        $last       = array_pop($results);
        return implode(' ', $results) . ' < ' . $last;
    }
}
