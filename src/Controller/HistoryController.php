<?php

namespace App\Controller;

use App\Entity\HistoryXml;
use App\Service\History;

use App\Service\HistoryService;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;


use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;




class HistoryController extends AbstractController
{

    private $em;

    private $historyService;


    public function __construct(
        HistoryService $historyService,
        ObjectManager $em
    ){
        $this->em = $em;
        $this->historyService = $historyService;
    }


    /**
     * @Route("/history/")

     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $response = new StreamedResponse() ;
        $response->setCallback(function () {
            $encoders = [new XmlEncoder(), new JsonEncoder()];
            $normalizers = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizers, $encoders);
            echo "<xmp>";
            echo "<?xml version=\"1.0\"?>\n";
            echo "<baseroot>\n";
            $count = 1;

            foreach($this->em->getRepository (\App\Entity\History::class)->findAll () as $history){
                echo $count . " => " . self::getNode($history, $serializer) . "\n";
                if ($count % 5 === 0 && $count !== 1) {
                    ob_flush();
                    flush();
                    //usleep(500000);
                }
                $count++;
            }
            echo "</baseroot>\n";
            echo "</xmp>";
            echo 'After unsetting the array.<br>';
            self::print_mem();
        });

        return $response;
    }


    public function getNode(\App\Entity\History $history, Serializer $serializer){
        $h = new HistoryXml();
        $h->setId ($history->getId());
        $content = $serializer->serialize($h, 'xml', []);
        $content = str_replace ('<?xml version="1.0"?>', '', $content);
        $content = str_replace ("\n", '', $content);
        return $content;
    }

    function print_mem()
    {
        /* Currently used memory */
        $mem_usage = memory_get_usage();

        /* Peak memory usage */
        $mem_peak = memory_get_peak_usage();

        echo 'The script is now using: <strong>' . round($mem_usage / 1024) . 'KB</strong> of memory.<br>';
        echo 'Peak usage: <strong>' . round($mem_peak / 1024) . 'KB</strong> of memory.<br><br>';
    }
    /**
     * @Route("/history3/")

     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index3()
    {
        $response = new Response() ;

            $encoders = [new XmlEncoder(), new JsonEncoder()];
            $normalizers = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizers, $encoders);
            //echo "<xmp>";
            //echo "<?xml version=\"1.0\"? >\n";
            //echo "<baseroot>\n";
            foreach($this->em->getRepository (\App\Entity\History::class)->findAll () as $history){

                // //$content = str_replace ('<?xml version="1.0"? >', '', $content);
                // $content = str_replace ("\n", '', $content). "\n";

                var_dump($history); die;
                $contents[] = $history;
                //echo $content . "\n";
                //ob_flush();
                //flush();
                //usleep(100000);
                echo 'After unsetting the array.<br>';
                self::print_mem();


            }
            //echo "</baseroot>\n";
            //echo "</xmp>";
        $c = $serializer->serialize($contents, 'xml', []);
            self::print_mem();

        $response->setContent ($c);
        return $response;
    }
}
