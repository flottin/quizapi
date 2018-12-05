<?php
namespace App\Service;

use App\Entity\Client;
use App\Entity\Type;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Question;

class History
{
    private $em;

    public function __construct ( ObjectManager $em  = null)
    {
        $this->em = $em;
    }

    /**
     * @param null $limit
     * @return array
     */
    public function getQuestions ($limit = null)
    {
        return $this->em
            ->getRepository ( Question::class )
            ->findBy ([], null, $limit);

    }


    public function setHistory(Client $client, String $pdfPath, String $mail, Type $type)
    {
        $history = new \App\Entity\History();

        $history->setClient($client);
        $history->setDateTime ();
        $history->setPdfPath ($pdfPath);
        $history->setMailMessage($mail);
        $history->setType($type);
        $this->em->persist($history);
        $this->em->flush();

    }


    public function setCmd(){
        //        $type = new Type();
        //        $type->setType('auto');
        //        $em->persist($type);
        //
        //        $type = new Type();
        //        $type->setType('manuel');
        //        $em->persist($type);
        //$em->flush();

        $client = $this->em->getRepository (Client::class)->find(1);
        $type = $this->em->getRepository (Type::class)->find(2);
        $name = $client->getName();
            //$historyService->setHistory ($client, "/web/clients/$name/pdf/rapport.pdf", 'texte mail', $type);


    }


}