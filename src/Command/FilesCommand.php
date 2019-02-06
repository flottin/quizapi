<?php
namespace App\Command;

use App\Entity\Client;
use App\Service\FtpService;
use App\Service\History;
use Doctrine\Common\Persistence\ObjectManager;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class FilesCommand extends Command
{

    protected static $defaultName = 'files';

    private $em;
    private $historyService;
    private $output;
    private $ftpService;
    private $request;
    private $container;

    public function __construct(ObjectManager $em
    , History $historyService
    , FtpService $ftpService
    , RequestStack $request
    , ContainerInterface $container
    )
    {
        parent::__construct ();
        $this->em               = $em;
        $this->historyService   = $historyService;
        $this->ftpService       = $ftpService;
        $this->request          = $request;
        $this->container        = $container;

    }

    protected function configure()
    {
        $this
            // ...
            ->addArgument('client',  InputArgument::REQUIRED, 'Client name')
            ->addArgument('run',  InputArgument::OPTIONAL, 'save data')
        ;
    }

    public function getRoorDir(){
        $path = $this->container->getParameter('kernel.root_dir');
        return str_replace ('src', '', $path);
    }

    public function getClient($name){
        return $this->em
            ->getRepository (Client::class)
            ->findOneBy(['name' => $name]);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $dry_run        = !(bool)$input->getArgument ('run');
        $rootPath       = self::getRoorDir ();
        $this->output   = $output;
        $client         = self::getClient($input->getArgument ('client'));

        $this->ftpService->connect ($client);
        foreach(self::listHistories ($client, $rootPath, $dry_run) as $h){
            $msg = "Store PdfPath " . $h->getPdfPath() . " \n";
            $this->output->writeln("<fg=green>$msg</>");
            $this->em->persist($h);
        }
        if (false === $dry_run){
            $this->em->flush ();

            $this->ftpService->putRecursive ($rootPath . 'var/rapports', '/home/flottin/rapports');
        }
    }

    public function listHistories($client, $rootPath, $dry_run){
        $criterias = ['client' => $client];
        $histories = $this->em->getRepository (\App\Entity\History::class)->findBy($criterias);
        foreach($histories as $history){
            yield self::copy ($history, $rootPath, $dry_run);
        }
    }
/**
 * http://localhost/web/clients/place/pdf/Rapport_production_PLACE-ORME-20190204-1641.pdf
 */
    public function copy(\App\Entity\History $history, $rootPath, $dry_run){
        $path           = explode('/', $history->getPdfPath ());
        $rpath          = array_reverse($path);
        $cpath          = array_chunk ($rpath, 5);
        $rcpath         = array_reverse($cpath[0]);
        $fileSource     = $rootPath . implode('/', $rcpath);
        if (file_exists ($fileSource) || true === $dry_run){
            $file = array_pop($path);
            preg_match('/[a-zA-Z-_]{10,50}-([0-9]{8,10})-[0-9]{4,6}.pdf/', $file, $matches);
            list($file, $fileDate) = $matches;
            preg_match('/([0-9]{4})([0-9]{2})/', $matches[1], $date);
            list($null, $year, $month) = $date;
            $datePath   = $year . '/' . $month;
            $pathYear   = $rootPath . 'var/rapports/' . $datePath;
            $pdfPath    = $datePath  . '/' . $file;
            if (!is_dir($pathYear)){
                mkdir($pathYear, 0777, true);
            }
            if (@copy($fileSource , $pathYear. '/' .$file) || true === $dry_run){
                $msg = "Copy $fileSource in $pathYear";
                $this->output->writeln("<fg=green>$msg</>");
                $history->setPdfPath($pdfPath);
                return $history;
            } else {
                $msg = "Error copyfile $fileSource ";
                $this->output->writeln("<fg=red>$msg</>");
                die;
            }
        }
        return null;
    }
}