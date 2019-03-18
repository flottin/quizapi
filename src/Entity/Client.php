<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 */
class Client
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
    private $name;


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
    public function getName ()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName ( $name ): void
    {
        $this->name = $name;
    }




}
