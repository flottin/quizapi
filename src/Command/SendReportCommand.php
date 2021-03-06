<?php
// src/Command/SendReportCommand.php
namespace App\Command;

use App\Entity\Client;
use App\Entity\Mailing;
use App\Entity\Type;
use App\Service\History;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendReportCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'report:send';

    private $em;
    private $historyService;

    public function __construct(ObjectManager $em
    , History $historyService
    )
    {
        parent::__construct ();
        $this->em = $em;
        $this->historyService = $historyService;

    }

    protected function configure()
    {
        $this
            // ...
            ->addArgument('client',  InputArgument::REQUIRED, 'Client name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {


        $client = $this->em->getRepository (Client::class)->findOneBy(['name' => $input->getArgument ('client')]);
        $type = $this->em->getRepository (Type::class)->findOneBy(['type'=>'auto']);
        for($i = 100; $i < 150 ; $i++)
        $this->historyService->setHistory ($client,  'path ' . $i, 'mail message ééééé', $type);



            die;

        //get message

        //get mailing list

        //get pdf
        $output->writeln ('<info>Client : '.$input->getArgument ('client').'</info>');
        $output->writeln ('<info>------------------------------</info>');
        $type = $this->em->getRepository (Type::class)->findBy(['type'=>'auto']);
        $mails = $this->em->getRepository (Mailing::class)->findBy(['type' => $type]);
        foreach ($mails as $mail){
            $email = $mail->getMail();
            $output->writeln ('<info>mail automatique : '.$mail->getMail().'</info>');
            self::sendMail($email);
        }
        $output->writeln ('<info>------------------------------</info>');
    }

    public function sendMail($email)
    {
        // get template

        // push mail with attachement
    }

    public function generate()
    {

    }
}