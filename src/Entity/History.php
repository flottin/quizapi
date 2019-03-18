<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HistoryRepository")
 */
class History
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"group1"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"group1"})
     */
    private $pdfPath;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"group1"})
     */
    private $dateTime;

    /**
     * @ORM\Column(type="string", length=2000)
     */
    private $mailMessage;



    /**
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="histories")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     * * @Groups({"group1"})
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity="Type")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     */
    private $type;

    /**
     * @return mixed
     */
    public function getId ()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId ( $id ): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getPdfPath ()
    {
        return $this->pdfPath;
    }

    /**
     * @param mixed $pdfPath
     */
    public function setPdfPath ( $pdfPath ): void
    {
        $this->pdfPath = $pdfPath;
    }

    /**
     * @return mixed
     */
    public function getDateTime ()
    {
        return $this->dateTime;
    }

    /**
     * @param mixed $dateTime
     */
    public function setDateTime (  ): void
    {
        $this->dateTime = new \DateTime('now');
    }

    /**
     * @return mixed
     */
    public function getMailMessage ()
    {
        return $this->mailMessage;
    }

    /**
     * @param mixed $mailMessage
     */
    public function setMailMessage ( $mailMessage ): void
    {
        $this->mailMessage = $mailMessage;
    }

    /**
     * @return mixed
     */
    public function getClient ()
    {
        return $this->client;
    }

    /**
     * @param mixed $client
     */
    public function setClient ( $client ): void
    {
        $this->client = $client;
    }

    /**
     * @return mixed
     */
    public function getType ()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType ( $type ): void
    {
        $this->type = $type;
    }




}
