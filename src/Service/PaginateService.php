<?php
namespace App\Service;

class PaginateService
{

    /**
     * @param $n
     * @param int $npp
     * @return int
     */
    public function page($n, $npp = 20){
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
    public function paginate($current, $p, $displayed = 20){
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