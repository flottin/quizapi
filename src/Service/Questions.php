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
            return $str . "traduction : " . $lang;
        }
        return $str . 'translate : ' . $lang;
    }


    /**
     * @param $n
     * @param int $npp
     * @return int
     */
    public function page($n, $npp = 10){
        if ($npp === 0 || $n === 0){
            return 0;
        } elseif ($n >= $npp){
            return (int) ceil($n / $npp);
        } else {
            return (int) floor($n / $npp) + 1;
        }
    }

    /**
     * @param $current
     * @param $p
     * @param int $displayed
     * @return array
     */
    public function paginate($current, $p, $displayed = 10){
        $page = self::page($current, $displayed);
        $res = [];
        $min = ($page - 1) * $displayed + 1;
        $max = $min + $displayed > $p ? $p : $min + $displayed;
        for($i =  $min; $i <= $max; $i++) {
            $res [$i] = $current === $i ? true : false;
        }
        return $res;
    }


}