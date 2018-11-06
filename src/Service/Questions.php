<?php
namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Question;

class Questions
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

    /**
     * @param string $str
     * @param string $lang
     * @return string
     */
    public function getLabel($str = '', $lang = 'en'){
        if ($lang == 'fr'){
            return $str . 'traduction : ' . $lang;
        }
        return $str . 'translate : ' . $lang;
    }
}