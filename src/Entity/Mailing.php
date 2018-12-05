<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Mailing
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mail;


    /**
     * @ORM\ManyToOne(targetEntity="Client")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
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
    public function getMail ()
    {
        return $this->mail;
    }

    /**
     * @param mixed $mail
     */
    public function setMail ( $mail ): void
    {
        $this->mail = $mail;
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
