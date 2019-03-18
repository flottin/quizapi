<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\History;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
// For annotations
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class QuizController extends AbstractController
{
    private $em;

    public function __construct(ObjectManager $em){
        $this->em = $em;
    }
    /**
     * @Route("/clients")
     */
    public function clients()
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $encoders = [new XmlEncoder('entity'), new JsonEncoder()];
        $normalizers = [new DateTimeNormalizer(), new ObjectNormalizer($classMetadataFactory)];
        $serializer = new Serializer($normalizers, $encoders);

        echo "<xmp><entities>\n";
        $entities = $this->em->getRepository (History::class)->findBy ([], null, 10);
        foreach ($entities as $entity){
            $serialized = $serializer->serialize(
                $entity,
                'xml',
                ['groups' => 'group1']
            );
            echo self::cleanNode ($serialized);
        }
        echo '</entities>';

        $response = new Response();
        return $response;
    }

    public function cleanNode($str){
        return "\t" . str_replace ("\n", '', str_replace ('<?xml version="1.0"?>', '', $str))  . "\n";
    }

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
