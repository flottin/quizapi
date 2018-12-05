<?php
/**
 * Created by PhpStorm.
 * User: flottin
 * Date: 05/12/2018
 * Time: 01:21
 */
// src/Command/CreateUserCommand.php
namespace App\Command;

use App\Entity\Mailing;
use App\Entity\Type;
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

    public function __construct(ObjectManager $em)
    {
        parent::__construct ();
        $this->em = $em;

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

    }

    public function generate()
    {

    }
}