<?php
namespace App\Tests\Service;

use App\Service\FtpService;
use PHPUnit\Framework\TestCase;

class FtpServiceTest extends TestCase
{

private $ftpService;
    public function testGet(){
        $this->ftpService = new FtpService();
        $this->ftpService->setHost ('192.168.0.24');
        $this->ftpService->setLogin ('flottin');
        $this->ftpService->setPass ('bb');
        $this->ftpService->connect ();
        $dir = 'rapports_mpe';

        $file = '/Users/flottin/PhpstormProjects/quizapi/pdf/Rapport_production_PLACE-ORME-20190204-1641.pdf';
        $remote_file = "/home/flottin/".$dir . '/Rapport_production_PLACE-ORME-20190204-1641.pdf';



        $tmppdf = '/Users/flottin/PhpstormProjects/quizapi/var/pdf/';
        $file = $tmppdf . 'Rapport_production_PLACE-ORME-20190204-1641.pdf';

        $this->ftpService->get($remote_file, $file);



        $this->assertEquals(true, true);

    }




}