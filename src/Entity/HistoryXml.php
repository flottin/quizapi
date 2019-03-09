<?php

namespace App\Entity;


class HistoryXml
{

    private $id;


    private $pdfPath;


    private $dateTime;


    private $mailMessage;


    private $client;


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
