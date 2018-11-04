<?php
namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Question;

class Questions
{
    private $em;

    public function __construct ( ObjectManager $em )
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
}