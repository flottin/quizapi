<?php
namespace App\Service;

use App\Entity\Client;
use App\Entity\Type;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Question;
use Symfony\Component\HttpFoundation\RequestStack;

class Mailing extends CommonService
{
    protected $em;

    public function __construct ( ObjectManager $em  = null, RequestStack $request)
    {
        $this->em = $em;
        //parent::__construct ($em, $request);
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

    public function save(Array $datas, Client $client){
        foreach ($datas as $name => $data){
            $type = str_replace('mailing', '', strtolower($name));
            $type = $this->em->getRepository (Type::class)->findOneBy(['type' => $type]);
            if (!empty($type)){
                self::setMailings($data, $client, $type);
            }
        }
        $this->em->flush();
    }

    public function setMailings(String $mailings, Client $client, Type $type)
    {
        $mails = explode(',', $mailings);
        self::remove($client, $type);
        foreach ($mails as $mailing) {
            self::setMailing ( $client, $mailing, $type );
        }
    }

    public function remove(Client $client, Type $type){
        $criterias = ['client' => $client, 'type' => $type];
        $mailings = $this->em->getRepository (\App\Entity\Mailing::class)->findBy($criterias);
        foreach ($mailings as $mailing){
            $this->em->remove($mailing);
        }
    }

    public function setMailing(Client $client, String $mail, Type $type, $flush = false)
    {
        $history = new \App\Entity\Mailing();
        $history->setClient($client);
        $history->setMail($mail);
        $history->setType($type);
        $this->em->persist($history);
        if (true === $flush){
            $this->em->flush();
        }
    }
}